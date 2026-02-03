<?php

namespace App\Http\Controllers;

use App\Models\BankStatement;
use App\Models\Payment;
use App\Models\ReconciliationMatch;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BankStatementImport;
use App\Models\AuditLog;
use App\Http\Controllers\PaymentController;

class ReconciliationController extends Controller
{
    public function index()
    {
        $statements = BankStatement::latest()->paginate(50);
        $pendingPayments = Payment::where('status', 'pending')->count();
        $unmatchedStatements = BankStatement::where('status', 'pending')->count();
        $matchRate = $this->getMatchRate();

        return view('admin.reconciliation.index', compact(
            'statements',
            'pendingPayments',
            'unmatchedStatements',
            'matchRate'
        ));
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'source' => 'required|in:mvola,orange,bni,bfv,airtel',
        ]);

        try {
            Excel::import(
                new BankStatementImport($request->source),
                $request->file('csv_file')
            );

            return back()->with('success', 'Relevé importé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'import : ' . $e->getMessage());
        }
    }

    public function matchOneClick()
    {
        // Charger tous les paiements en attente, groupés par montant pour accès rapide
        $pendingPayments = Payment::where('status', 'pending')->get()->groupBy(function ($p) {
            return (string) $p->amount;
        });

        $statements = BankStatement::where('status', 'pending')->get();

        $matched = 0;
        $exceptions = 0;

        foreach ($statements as $statement) {
            $amountKey = (string) $statement->amount;
            $candidates = $pendingPayments->get($amountKey);

            $bestMatch = null;
            $bestScore = 0;

            if ($candidates) {
                foreach ($candidates as $payment) {
                    $score = $this->calculateMatchScore($payment, $statement);

                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $bestMatch = $payment;
                    }
                }
            }

            // Seuil de confiance à 85% pour le rapprochement automatique
            if ($bestMatch && $bestScore >= 85) {
                ReconciliationMatch::create([
                    'payment_id' => $bestMatch->id,
                    'bank_statement_id' => $statement->id,
                    'score' => $bestScore,
                    'status' => 'auto',
                    'matched_by' => auth()->id(),
                ]);

                $bestMatch->update([
                    'status' => 'paid',
                    'paid_at' => $statement->date, // On utilise la date du relevé
                    'metadata' => array_merge($bestMatch->metadata ?? [], [
                        'reconciled_at' => now()->toDateTimeString(),
                        'reconciliation_score' => $bestScore
                    ])
                ]);

                $statement->update(['status' => 'matched']);

                // Générer le reçu
                app(PaymentController::class)->generateReceipt($bestMatch);

                AuditLog::create([
                    'event' => 'reconciliation_auto',
                    'auditable_type' => BankStatement::class,
                    'auditable_id' => $statement->id,
                    'user_id' => auth()->id(),
                    'description' => "Rapprochement automatique réussi pour {$bestMatch->transaction_id} (Score: {$bestScore}%)",
                    'new_values' => ['payment_id' => $bestMatch->id, 'status' => 'matched'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $matched++;
            } else {
                $statement->update(['status' => 'exception']);
                $exceptions++;
            }
        }

        return back()->with('success', "Rapprochement automatique terminé : {$matched} appariés avec succès (>85% confiance), {$exceptions} transactions nécessitent une revue manuelle.");
    }



    private function calculateMatchScore(Payment $payment, BankStatement $statement): int
    {
        $score = 0;

        // 1. Montant exact (CRITIQUE)
        if ($payment->amount == $statement->amount) {
            $score += 40;
        } else {
            // Si le montant ne correspond pas, le score reste très bas
            return 0;
        }

        // 2. Référence de transaction
        if ($payment->transaction_id === $statement->reference) {
            $score += 50; // Match parfait de référence
        } elseif (str_contains($statement->reference, $payment->transaction_id)) {
            $score += 40; // Référence contenue dans le libellé
        }

        // 3. Date de transaction
        $daysDiff = abs($payment->created_at->diffInDays($statement->date));
        if ($daysDiff === 0) {
            $score += 15; // Même jour
        } elseif ($daysDiff <= 2) {
            $score += 10; // Proche (±2 jours)
        } elseif ($daysDiff <= 5) {
            $score += 5;  // Acceptable (±5 jours)
        }

        // 4. Identification de l'étudiant dans le libellé (Nom ou Matricule)
        $user = $payment->user;
        $name = strtoupper($user->name);
        $statementRef = strtoupper($statement->reference);

        // Recherche du nom (si assez long pour éviter les faux positifs)
        if (strlen($name) > 5 && str_contains($statementRef, $name)) {
            $score += 10;
        }

        // Recherche du matricule (si l'utilisateur est un étudiant associé)
        // On suppose que le matricule est stocké ou peut être récupéré via une relation
        // Pour l'instant on check si le matricule est dans la référence du paiement (souvent le cas)
        if (str_contains($statementRef, $payment->transaction_id)) {
            // Déjà géré par le point 2, mais on pourrait chercher un matricule spécifique ici
        }

        return min(100, $score);
    }

    public function report()
    {
        $data = [
            'total_payments' => Payment::count(),
            'paid_payments' => Payment::where('status', 'paid')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'total_statements' => BankStatement::count(),
            'matched_statements' => BankStatement::where('status', 'matched')->count(),
            'exception_statements' => BankStatement::where('status', 'exception')->count(),
            'match_rate' => $this->getMatchRate(),
            'recent_matches' => ReconciliationMatch::with(['payment.user', 'bankStatement'])
                ->latest()
                ->take(20)
                ->get(),
        ];

        return view('admin.reconciliation.report', compact('data'));
    }

    private function getMatchRate(): float
    {
        $total = BankStatement::count();
        if ($total === 0)
            return 0;

        $matched = BankStatement::where('status', 'matched')->count();
        return round(($matched / $total) * 100, 2);
    }

    public function manualMatch(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'bank_statement_id' => 'required|exists:bank_statements,id',
            'reason' => 'required|string|min:10',
        ]);

        $payment = Payment::findOrFail($request->payment_id);
        $statement = BankStatement::findOrFail($request->bank_statement_id);

        // Vérifier si déjà matché
        if ($payment->status === 'paid' || $statement->status === 'matched') {
            return back()->with('error', 'Ce paiement ou ce relevé est déjà associé.');
        }

        ReconciliationMatch::create([
            'payment_id' => $payment->id,
            'bank_statement_id' => $statement->id,
            'score' => $this->calculateMatchScore($payment, $statement),
            'status' => 'manual',
            'note' => $request->reason,
            'matched_by' => auth()->id(),
        ]);

        $payment->update([
            'status' => 'paid',
            'paid_at' => $statement->date,
            'metadata' => array_merge($payment->metadata ?? [], [
                'manual_match_reason' => $request->reason,
                'matched_by' => auth()->id(),
                'reconciled_at' => now()->toDateTimeString()
            ])
        ]);

        $statement->update(['status' => 'matched']);

        // Générer le reçu
        app(PaymentController::class)->generateReceipt($payment);

        AuditLog::create([
            'event' => 'reconciliation_manual',
            'auditable_type' => BankStatement::class,
            'auditable_id' => $statement->id,
            'user_id' => auth()->id(),
            'description' => "Rapprochement manuel effectué pour {$payment->transaction_id} par " . auth()->user()->name,
            'new_values' => ['payment_id' => $payment->id, 'status' => 'matched', 'reason' => $request->reason],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Appariement manuel effectué avec succès.');
    }

    public function requestJustification(Request $request, BankStatement $statement)
    {
        $request->validate(['message' => 'required|string']);

        // Tentative d'identification de l'étudiant via le libellé pour envoyer l'email
        // (Logique simplifiée : on cherche un utilisateur dont le nom est dans la référence)
        $potentialUser = null;
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            if (str_contains(strtoupper($statement->reference), strtoupper($user->name))) {
                $potentialUser = $user;
                break;
            }
        }

        if ($potentialUser) {
            try {
                \Illuminate\Support\Facades\Mail::to($potentialUser->email)->queue(new \App\Mail\JustificationRequestMail($statement, $request->message));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Erreur envoi email justification : ' . $e->getMessage());
            }
        }

        $statement->update([
            'status' => 'exception',
            'raw_data' => array_merge($statement->raw_data ?? [], [
                'justification_requested' => true,
                'justification_message' => $request->message,
                'requested_at' => now()->toDateTimeString(),
                'requested_by' => auth()->id(),
                'notified_user_id' => $potentialUser ? $potentialUser->id : null
            ])
        ]);

        AuditLog::create([
            'event' => 'reconciliation_justification_requested',
            'auditable_type' => BankStatement::class,
            'auditable_id' => $statement->id,
            'user_id' => auth()->id(),
            'description' => "Demande de justificatif envoyée pour le relevé {$statement->reference}" . ($potentialUser ? " à {$potentialUser->name}" : ""),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Demande de justificatif enregistrée' . ($potentialUser ? ' et email envoyé à ' . $potentialUser->name : '.'));
    }

    public function exceptions()
    {
        $exceptionStatements = BankStatement::where('status', 'exception')
            ->latest()
            ->paginate(20);

        $pendingPayments = Payment::where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.reconciliation.exceptions', compact('exceptionStatements', 'pendingPayments'));
    }
}
