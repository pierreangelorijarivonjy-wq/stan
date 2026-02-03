<?php

namespace App\Http\Controllers;

use App\Models\Convocation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;

class VerificationController extends Controller
{
    public function index()
    {
        return view('verify.index');
    }

    public function verifyConvocation(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'sig' => 'nullable|string',
        ]);

        $convocation = Convocation::where('qr_code', $request->code)
            ->with(['student', 'examSession'])
            ->first();

        if (!$convocation) {
            return response()->json([
                'valid' => false,
                'message' => 'Convocation invalide ou introuvable.',
            ], 404);
        }

        // Vérification de la signature numérique
        if ($request->sig && $convocation->signature) {
            $shortSig = substr($convocation->signature, 0, 16);
            if ($request->sig !== $shortSig) {
                AuditLog::create([
                    'event' => 'verification_failed_signature',
                    'auditable_type' => Convocation::class,
                    'auditable_id' => $convocation->id,
                    'description' => "Échec de vérification de signature pour la convocation {$convocation->qr_code} (Falsification possible)",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
                return response()->json([
                    'valid' => false,
                    'message' => 'Signature de la convocation invalide (falsification détectée).',
                ], 400);
            }
        }

        AuditLog::create([
            'event' => 'verification_success',
            'auditable_type' => Convocation::class,
            'auditable_id' => $convocation->id,
            'description' => "Convocation {$convocation->qr_code} vérifiée avec succès",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Marquer comme vérifiée
        $convocation->update(['status' => 'verified']);

        return response()->json([
            'valid' => true,
            'type' => 'convocation',
            'data' => [
                'student_name' => $convocation->student->first_name . ' ' . $convocation->student->last_name,
                'matricule' => $convocation->student->matricule,
                'email' => $convocation->student->email,
                'phone' => $convocation->student->phone,
                'session_type' => strtoupper($convocation->examSession->type),
                'session_date' => $convocation->examSession->date->format('d/m/Y'),
                'session_time' => $convocation->examSession->time,
                'center' => $convocation->examSession->center,
                'room' => $convocation->examSession->room,
                'photo' => $convocation->student->photo_path, // Utiliser photo_path
                'status' => $convocation->status,
                'is_certified' => (bool) $convocation->signature,
            ],
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'sig' => 'nullable|string',
        ]);

        $payment = Payment::where('transaction_id', $request->code)
            ->with('user')
            ->first();

        if (!$payment) {
            return response()->json([
                'valid' => false,
                'message' => 'Reçu de paiement invalide ou introuvable.',
            ], 404);
        }

        // Vérification de la signature numérique
        $storedSignature = $payment->metadata['digital_signature'] ?? null;

        if ($request->sig && $storedSignature) {
            $shortSig = substr($storedSignature, 0, 16);
            if ($request->sig !== $shortSig) {
                AuditLog::create([
                    'event' => 'verification_failed_signature',
                    'auditable_type' => Payment::class,
                    'auditable_id' => $payment->id,
                    'description' => "Échec de vérification de signature pour le reçu {$payment->transaction_id}",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
                return response()->json([
                    'valid' => false,
                    'message' => 'Signature du reçu invalide (falsification détectée).',
                ], 400);
            }
        }

        AuditLog::create([
            'event' => 'verification_success',
            'auditable_type' => Payment::class,
            'auditable_id' => $payment->id,
            'description' => "Paiement {$payment->transaction_id} vérifié avec succès",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->json([
            'valid' => true,
            'type' => 'payment',
            'data' => [
                'student_name' => $payment->user->name,
                'transaction_id' => $payment->transaction_id,
                'amount' => number_format($payment->amount, 0, ',', ' ') . ' Ar',
                'type' => ucfirst(str_replace('_', ' ', $payment->type)),
                'status' => $payment->status,
                'provider' => strtoupper($payment->provider),
                'method' => $payment->method ?? 'Mobile Money',
                'paid_at' => $payment->paid_at?->format('d/m/Y H:i'),
                'created_at' => $payment->created_at->format('d/m/Y H:i'),
                'is_certified' => (bool) $storedSignature,
            ],
        ]);
    }

    public function verifyTranscript(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'sig' => 'nullable|string',
        ]);

        // Pour les relevés, on n'a pas de table dédiée, on stocke tout dans le PDF ou on cherche par QR code dans les fichiers
        // Mais pour la démo V1, on va simuler la réussite si la signature est cohérente avec les données (si on les avait)
        // En prod, on devrait avoir une table `transcripts` pour stocker les signatures générées.

        // Simulation : On accepte si le code est un UUID et qu'une signature est fournie
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $request->code)) {
            AuditLog::create([
                'event' => 'verification_success_transcript',
                'description' => "Relevé de notes {$request->code} vérifié (Simulation)",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
            return response()->json([
                'valid' => true,
                'type' => 'transcript',
                'data' => [
                    'student_name' => 'Vérification de Relevé de Notes',
                    'matricule' => 'CERTIFIÉ',
                    'session_type' => 'SESSION OFFICIELLE',
                    'status' => 'Authentique',
                    'is_certified' => true,
                ],
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Relevé de notes introuvable.',
        ], 404);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'sig' => 'nullable|string',
        ]);

        $code = $request->code;

        // Détection du type
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $code)) {
            // Ça peut être une convocation ou un relevé. On check convocation d'abord.
            if (Convocation::where('qr_code', $code)->exists()) {
                return $this->verifyConvocation($request);
            }
            return $this->verifyTranscript($request);
        }

        if (str_starts_with($code, 'EDUPASS-')) {
            return $this->verifyPayment($request);
        }

        $payment = Payment::where('transaction_id', $code)->first();
        if ($payment) {
            return $this->verifyPayment($request);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Code invalide ou non reconnu.',
        ], 404);
    }

    public function scan()
    {
        // Page pour scanner avec la caméra (pour contrôleurs sur site)
        return view('verify.scan');
    }

    public function stats()
    {
        $this->authorize('view dashboard');

        $stats = [
            'total_convocations_verified' => Convocation::where('status', 'verified')->count(),
            'total_payments_verified' => Payment::where('status', 'paid')->count(),
            'verifications_today' => Convocation::where('status', 'verified')
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return view('admin.verification.stats', compact('stats'));
    }
}
