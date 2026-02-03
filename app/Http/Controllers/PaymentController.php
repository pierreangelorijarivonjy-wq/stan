<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\AuditLog;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    // Affiche le formulaire (MVola / Orange)
    public function showForm()
    {
        return view('payments.index');
    }

    // VRAI PAIEMENT – appelé par ton formulaire
    public function initiate(Request $request)
    {
        Log::info('Payment Initiate Called', $request->all());
        $request->validate([
            'provider' => 'required|in:mvola,orange,airtel,card,transfer',
            'phone' => 'required_if:provider,mvola,orange,airtel|nullable|regex:/^03[2348]\d{7}$/',
            'amount' => 'required|numeric|min:100',
            'type' => 'required|in:inscription,reinscription,examen,scolarite'
        ]);

        try {
            $result = $this->paymentService->initiate(
                Auth::user(),
                $request->provider,
                $request->phone ?? '',
                $request->amount,
                $request->type
            );

            if (isset($result['redirect'])) {
                return redirect()->away($result['redirect']);
            }

        } catch (\Exception $e) {
            Log::error('🔴 ERREUR PAIEMENT CONTROLLER', [
                'message' => $e->getMessage()
            ]);
            return back()->with('error', 'Erreur paiement : ' . $e->getMessage());
        }

        return back()->with('error', 'Erreur inconnue lors de l\'initialisation du paiement.');
    }

    // Page d'attente / Simulation
    public function waiting(Payment $payment)
    {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }
        return view('payments.waiting', compact('payment'));
    }

    // Polling status check
    public function checkStatus(Payment $payment)
    {
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        // Active check for MVola if pending
        if ($payment->status === 'pending' && $payment->provider === 'mvola') {
            $statusData = $this->paymentService->checkMvolaStatus($payment);
            
            if ($statusData && isset($statusData['status'])) {
                $apiStatus = strtolower($statusData['status']); // completed, failed, pending
                
                if ($apiStatus === 'completed' || $apiStatus === 'success') {
                    if ($payment->status !== 'paid') {
                        $payment->update([
                            'status' => 'paid',
                            'paid_at' => now(),
                            'metadata' => array_merge($payment->metadata ?? [], ['mvola_check' => 'completed'])
                        ]);
                        
                        // Success triggers
                        $this->generateReceipt($payment);
                        try {
                            $payment->user->notify(new \App\Notifications\PaymentConfirmed($payment));
                        } catch (\Exception $e) {
                            Log::error('Auth Notification Error: ' . $e->getMessage());
                        }
                        event(new \App\Events\PaymentCompleted($payment));
                    }
                } elseif ($apiStatus === 'failed') {
                    $payment->update([
                        'status' => 'failed',
                        'metadata' => array_merge($payment->metadata ?? [], ['mvola_check' => 'failed'])
                    ]);
                }
            }
        }

        return response()->json(['status' => $payment->status]);
    }

    // Simulation Webhook (Sandbox)
    public function simulateWebhook(Request $request)
    {
        $payment = Payment::where('transaction_id', $request->transaction_id)->firstOrFail();

        if ($request->action === 'confirm') {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now()
            ]);

            // Trigger standard success flow
            $this->generateReceipt($payment);
            $payment->user->notify(new \App\Notifications\PaymentConfirmed($payment));

            // Dispatch payment completed event
            event(new \App\Events\PaymentCompleted($payment));

            return redirect()->route('payment.success');
        }

        if ($request->action === 'cancel') {
            $payment->update(['status' => 'failed']);
            $payment->user->notify(new \App\Notifications\PaymentRejected($payment, 'Annulé par l\'utilisateur ou échec technique'));

            // Dispatch payment failed event
            event(new \App\Events\PaymentFailed($payment, 'Annulé par l\'utilisateur ou échec technique'));

            return redirect()->route('payment.cancel');
        }

        return back();
    }

    // Retours
    public function success()
    {
        return view('payments.success');
    }
    public function cancel()
    {
        return view('payments.cancel');
    }

    // Webhook – confirmation automatique
    public function webhook(Request $request)
    {
        Log::info('Webhook reçu', $request->all());

        $ref = $request->requestingOrganisationTransactionReference
            ?? $request->order_id
            ?? $request->transaction_id;

        if ($ref && in_array($request->status ?? $request->transactionStatus, ['SUCCESS', 'completed'])) {
            $payment = Payment::where('transaction_id', $ref)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);

                // Générer automatiquement le reçu
                $this->generateReceipt($payment);

                // Envoyer notification in-app
                $payment->user->notify(new \App\Notifications\PaymentConfirmed($payment));

                // Dispatch payment completed event
                event(new \App\Events\PaymentCompleted($payment));
            }
        }


        return response('OK', 200);
    }

    // Générer reçu PDF avec QR code
    public function generateReceipt(Payment $payment)
    {
        // Générer une signature numérique (Hash sécurisé)
        $signatureData = [
            'id' => $payment->id,
            'transaction_id' => $payment->transaction_id,
            'amount' => $payment->amount,
            'user_id' => $payment->user_id,
            'secret' => config('app.key')
        ];
        $digitalSignature = hash_hmac('sha256', json_encode($signatureData), config('app.key'));

        // Générer QR code avec URL de vérification incluant la signature
        $verifyUrl = route('verify.payment', [
            'code' => $payment->transaction_id,
            'sig' => substr($digitalSignature, 0, 16) // Signature courte pour le QR
        ]);

        $qrCode = \Endroid\QrCode\QrCode::create($verifyUrl)
            ->setSize(200)
            ->setMargin(10);

        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        // Générer PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', [
            'payment' => $payment,
            'qrCodeImage' => $qrCodeBase64,
            'signature' => $digitalSignature
        ]);
        $pdf->setPaper('a4', 'portrait');

        // Stocker
        $path = "receipts/{$payment->transaction_id}.pdf";
        \Illuminate\Support\Facades\Storage::put($path, $pdf->output());

        $payment->update([
            'receipt_url' => $path,
            'metadata' => array_merge($payment->metadata ?? [], ['digital_signature' => $digitalSignature])
        ]);

        // Envoyer email avec reçu
        try {
            \Illuminate\Support\Facades\Mail::to($payment->user->email)->queue(new \App\Mail\PaymentReceiptMail($payment));
        } catch (\Exception $e) {
            Log::error('Erreur envoi email reçu : ' . $e->getMessage());
        }

        return $pdf;
    }


    // Télécharger reçu
    public function downloadReceipt(Payment $payment)
    {
        // Vérifier que l'utilisateur peut télécharger ce reçu
        if ($payment->user_id !== auth()->id() && !auth()->user()->hasRole(['admin', 'comptable'])) {
            abort(403, 'Accès non autorisé.');
        }

        if (!$payment->receipt_url || !\Illuminate\Support\Facades\Storage::exists($payment->receipt_url)) {
            // Générer le reçu s'il n'existe pas
            $pdf = $this->generateReceipt($payment);

            // Dispatch file download event
            event(new \App\Events\FileDownloaded(
                auth()->user(),
                'pdf',
                "recu-{$payment->transaction_id}.pdf",
                'payment_receipt'
            ));

            return $pdf->download("recu-{$payment->transaction_id}.pdf");
        }

        // Dispatch file download event
        event(new \App\Events\FileDownloaded(
            auth()->user(),
            'pdf',
            "recu-{$payment->transaction_id}.pdf",
            'payment_receipt'
        ));

        return \Illuminate\Support\Facades\Storage::download($payment->receipt_url, "recu-{$payment->transaction_id}.pdf");
    }

    // Upload preuve de paiement (pour virements)
    public function uploadProof(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'proof' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        // Vérifier que c'est bien le paiement de l'utilisateur
        if ($payment->user_id !== auth()->id()) {
            abort(403, 'Accès non autorisé.');
        }

        $path = $request->file('proof')->store('payment-proofs');

        $payment->update([
            'metadata' => array_merge($payment->metadata ?? [], ['proof_path' => $path])
        ]);

        AuditLog::create([
            'event' => 'payment_proof_uploaded',
            'auditable_type' => Payment::class,
            'auditable_id' => $payment->id,
            'user_id' => auth()->id(),
            'description' => "Preuve de paiement téléchargée pour la transaction {$payment->transaction_id}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', 'Preuve de paiement envoyée avec succès.');
    }

    // Historique des paiements
    public function history()
    {
        $payments = auth()->user()->payments()->latest()->paginate(20);
        return view('payments.history', compact('payments'));
    }

    // Vue globale pour Admin/Comptable
    public function allPayments(Request $request)
    {
        $query = Payment::with('user')->latest();

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            })
                ->orWhere('transaction_id', 'like', "%{$request->search}%")
                ->orWhere('amount', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $payments = $query->paginate(50);
        return view('admin.payments.index', compact('payments'));
    }

    public function validateProof(Request $request, Payment $payment)
    {
        $request->validate(['note' => 'nullable|string']);

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'metadata' => array_merge($payment->metadata ?? [], [
                'validated_by' => auth()->id(),
                'validation_note' => $request->note,
                'validated_at' => now()->toDateTimeString()
            ])
        ]);

        AuditLog::create([
            'event' => 'payment_validated',
            'auditable_type' => Payment::class,
            'auditable_id' => $payment->id,
            'user_id' => auth()->id(),
            'description' => "Paiement {$payment->transaction_id} validé par " . auth()->user()->name,
            'new_values' => ['status' => 'paid'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->generateReceipt($payment);
        $payment->user->notify(new \App\Notifications\PaymentConfirmed($payment));

        return back()->with('success', 'Paiement validé avec succès.');
    }

    public function rejectProof(Request $request, Payment $payment)
    {
        $request->validate(['reason' => 'required|string']);

        $payment->update([
            'status' => 'failed',
            'metadata' => array_merge($payment->metadata ?? [], [
                'rejected_by' => auth()->id(),
                'rejection_reason' => $request->reason,
                'rejected_at' => now()->toDateTimeString()
            ])
        ]);

        AuditLog::create([
            'event' => 'payment_rejected',
            'auditable_type' => Payment::class,
            'auditable_id' => $payment->id,
            'user_id' => auth()->id(),
            'description' => "Paiement {$payment->transaction_id} rejeté par " . auth()->user()->name . ". Raison : " . $request->reason,
            'new_values' => ['status' => 'failed'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $payment->user->notify(new \App\Notifications\PaymentRejected($payment, $request->reason));

        return back()->with('success', 'Paiement rejeté.');
    }

    public function addInternalNote(Request $request, Payment $payment)
    {
        $request->validate(['note' => 'required|string']);

        $notes = $payment->metadata['internal_notes'] ?? [];
        $notes[] = [
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'note' => $request->note,
            'created_at' => now()->toDateTimeString()
        ];

        $payment->update([
            'metadata' => array_merge($payment->metadata ?? [], ['internal_notes' => $notes])
        ]);

        return back()->with('success', 'Note interne ajoutée.');
    }
}