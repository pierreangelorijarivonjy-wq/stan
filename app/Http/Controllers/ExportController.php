<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Convocation;
use App\Models\BankStatement;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    /**
     * Exporter les paiements en CSV
     */
    public function paymentsCSV(Request $request)
    {
        $query = Payment::with('user');

        // Filtres optionnels
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $payments = $query->get();

        $csv = $this->generatePaymentsCSV($payments);

        $filename = 'paiements_' . date('Y-m-d_His') . '.csv';

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Exporter les paiements en PDF
     */
    public function paymentsPDF(Request $request)
    {
        $query = Payment::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $payments = $query->get();

        $pdf = PDF::loadView('pdf.payments-report', [
            'payments' => $payments,
            'total' => $payments->where('status', 'paid')->sum('amount'),
            'filters' => $request->all(),
        ]);

        $filename = 'rapport_paiements_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Exporter le rapport de rapprochement bancaire en CSV
     */
    public function reconciliationCSV(Request $request)
    {
        $statements = BankStatement::with(['reconciliationMatch.payment'])
            ->when($request->has('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->get();

        $csv = $this->generateReconciliationCSV($statements);

        $filename = 'rapprochement_' . date('Y-m-d_His') . '.csv';

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Exporter le rapport de rapprochement en PDF
     */
    public function reconciliationPDF(Request $request)
    {
        $statements = BankStatement::with(['reconciliationMatch.payment'])
            ->when($request->has('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->get();

        $matched = $statements->where('status', 'matched')->count();
        $total = $statements->count();
        $matchRate = $total > 0 ? round(($matched / $total) * 100, 2) : 0;

        $pdf = PDF::loadView('pdf.reconciliation-report', [
            'statements' => $statements,
            'matched' => $matched,
            'total' => $total,
            'matchRate' => $matchRate,
        ]);

        $filename = 'rapport_rapprochement_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Exporter les logs d'audit en CSV
     */
    public function auditLogsCSV(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->has('event')) {
            $query->where('event', $request->event);
        }

        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->latest()->limit(10000)->get();

        $csv = $this->generateAuditLogsCSV($logs);

        $filename = 'audit_logs_' . date('Y-m-d_His') . '.csv';

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Exporter les étudiants en CSV
     */
    public function studentsCSV(Request $request)
    {
        $query = \App\Models\Student::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->get();

        $csv = $this->generateStudentsCSV($students);

        $filename = 'etudiants_' . date('Y-m-d_His') . '.csv';

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Générer CSV des étudiants
     */
    protected function generateStudentsCSV($students): string
    {
        $output = fopen('php://temp', 'r+');

        // En-têtes
        fputcsv($output, [
            'ID',
            'Matricule',
            'Nom',
            'Prénom',
            'Email',
            'Téléphone',
            'Statut',
            'Date Inscription',
        ]);

        // Données
        foreach ($students as $student) {
            fputcsv($output, [
                $student->id,
                $student->matricule,
                $student->last_name,
                $student->first_name,
                $student->email,
                $student->phone,
                $student->status,
                $student->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    /**
     * Générer CSV des paiements
     */
    protected function generatePaymentsCSV($payments): string
    {
        $output = fopen('php://temp', 'r+');

        // En-têtes
        fputcsv($output, [
            'ID',
            'Date',
            'Étudiant',
            'Email',
            'Montant',
            'Type',
            'Fournisseur',
            'Statut',
            'Transaction ID',
            'Téléphone',
        ]);

        // Données
        foreach ($payments as $payment) {
            fputcsv($output, [
                $payment->id,
                $payment->created_at->format('Y-m-d H:i:s'),
                $payment->user->name ?? 'N/A',
                $payment->user->email ?? 'N/A',
                $payment->amount,
                $payment->type,
                $payment->provider,
                $payment->status,
                $payment->transaction_id,
                $payment->phone,
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    /**
     * Générer CSV du rapprochement
     */
    protected function generateReconciliationCSV($statements): string
    {
        $output = fopen('php://temp', 'r+');

        // En-têtes
        fputcsv($output, [
            'ID',
            'Date Transaction',
            'Référence',
            'Montant',
            'Statut',
            'Paiement Associé',
            'Confiance',
        ]);

        // Données
        foreach ($statements as $statement) {
            fputcsv($output, [
                $statement->id,
                $statement->transaction_date,
                $statement->reference,
                $statement->amount,
                $statement->status,
                $statement->reconciliationMatch?->payment?->transaction_id ?? 'N/A',
                $statement->reconciliationMatch?->confidence_score ?? 'N/A',
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    /**
     * Générer CSV des logs d'audit
     */
    protected function generateAuditLogsCSV($logs): string
    {
        $output = fopen('php://temp', 'r+');

        // En-têtes
        fputcsv($output, [
            'ID',
            'Date',
            'Utilisateur',
            'Événement',
            'Type',
            'ID Entité',
            'IP',
            'Description',
        ]);

        // Données
        foreach ($logs as $log) {
            fputcsv($output, [
                $log->id,
                $log->created_at->format('Y-m-d H:i:s'),
                $log->user?->name ?? 'Système',
                $log->event,
                class_basename($log->auditable_type),
                $log->auditable_id,
                $log->ip_address,
                $log->readable_description,
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}
