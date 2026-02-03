<?php

namespace App\Http\Controllers;

use App\Models\Convocation;
use App\Models\ExamSession;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\AuditLog;

class ConvocationController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('student')) {
            $student = auth()->user()->student;
            if (!$student) {
                return redirect()->route('dashboard')->with('error', 'Profil étudiant non trouvé.');
            }
            $convocations = $student->convocations()->with('examSession')->latest()->get();
        } else {
            $convocations = Convocation::with(['student', 'examSession'])->latest()->paginate(50);
        }

        return view('convocations.index', compact('convocations'));
    }

    public function deactivate(Request $request, Convocation $convocation)
    {
        $this->authorize('generate convocations');

        $request->validate([
            'reason' => 'required|string'
        ]);

        $convocation->update([
            'status' => 'deactivated',
            'deactivated_at' => now(),
            'deactivation_reason' => $request->reason
        ]);

        return back()->with('success', 'Convocation désactivée.');
    }

    public function scan(Request $request, $qrCode)
    {
        $convocation = Convocation::where('qr_code', $qrCode)->firstOrFail();

        if ($convocation->status === 'deactivated') {
            return response()->json(['status' => 'error', 'message' => 'Convocation invalide ou désactivée.'], 403);
        }

        $convocation->update([
            'scanned_at' => now(),
            'scanned_by' => auth()->id()
        ]);

        return response()->json([
            'status' => 'success',
            'student' => $convocation->student->name,
            'session' => $convocation->examSession->type,
            'scanned_at' => $convocation->scanned_at->toDateTimeString()
        ]);
    }

    public function create()
    {
        $this->authorize('generate convocations');

        $sessions = ExamSession::where('status', 'planned')->get();
        $students = Student::where('status', 'active')->get();

        return view('admin.convocations.create', compact('sessions', 'students'));
    }

    public function generate(Request $request)
    {
        $this->authorize('generate convocations');

        $request->validate([
            'exam_session_id' => 'required|exists:exam_sessions,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $session = ExamSession::findOrFail($request->exam_session_id);
        $generated = 0;

        foreach ($request->student_ids as $studentId) {
            $student = Student::findOrFail($studentId);

            // Vérifier si convocation existe déjà
            if (
                Convocation::where('student_id', $student->id)
                    ->where('exam_session_id', $session->id)
                    ->exists()
            ) {
                continue;
            }

            // Générer QR code unique
            $qrCode = Str::uuid()->toString();

            // Créer convocation
            $convocation = Convocation::create([
                'student_id' => $student->id,
                'exam_session_id' => $session->id,
                'qr_code' => $qrCode,
                'status' => 'generated',
            ]);

            // Générer PDF
            $this->generatePDF($convocation);

            $generated++;
        }

        AuditLog::create([
            'event' => 'convocations_generated',
            'auditable_type' => ExamSession::class,
            'auditable_id' => $session->id,
            'user_id' => auth()->id(),
            'description' => "{$generated} convocations générées pour la session {$session->type}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', "{$generated} convocations générées avec succès.");
    }

    private function generatePDF(Convocation $convocation)
    {
        $student = $convocation->student;
        $session = $convocation->examSession;
        $timestamp = now()->toDateTimeString();

        // Générer une signature numérique (Hash sécurisé)
        $signatureData = [
            'id' => $convocation->id,
            'qr_code' => $convocation->qr_code,
            'student_id' => $student->id,
            'session_id' => $session->id,
            'secret' => config('app.key')
        ];
        $digitalSignature = hash_hmac('sha256', json_encode($signatureData), config('app.key'));

        // Générer QR code avec URL de vérification incluant la signature
        $verifyUrl = route('verify.convocation', [
            'code' => $convocation->qr_code,
            'sig' => substr($digitalSignature, 0, 16) // Signature courte pour le QR
        ]);

        $qrCode = \Endroid\QrCode\QrCode::create($verifyUrl)
            ->setSize(200)
            ->setMargin(10);

        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        // Générer PDF
        $pdf = PDF::loadView('pdf.convocation', [
            'convocation' => $convocation,
            'student' => $student,
            'session' => $session,
            'qrCodeImage' => $qrCodeBase64,
            'timestamp' => $timestamp,
            'signature' => $digitalSignature
        ]);

        // Sécuriser le PDF avec un mot de passe (le matricule de l'étudiant)
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        if (method_exists($canvas, 'get_cpdf')) {
            $canvas->get_cpdf()->setEncryption($student->matricule, config('app.key'));
        }

        // Stocker
        $path = "convocations/{$convocation->qr_code}.pdf";
        Storage::put($path, $pdf->output());

        $convocation->update([
            'pdf_url' => $path,
            'signature' => $digitalSignature,
        ]);
    }

    public function download(Convocation $convocation)
    {
        // Vérifier les permissions
        if (auth()->user()->hasRole('student')) {
            if ($convocation->student->user_id !== auth()->id()) {
                abort(403, 'Accès non autorisé.');
            }
        }

        if (!$convocation->pdf_url || !Storage::exists($convocation->pdf_url)) {
            return back()->with('error', 'PDF non disponible.');
        }

        $convocation->update([
            'downloaded_at' => now(),
            'status' => 'downloaded'
        ]);

        // Dispatch file download event
        event(new \App\Events\FileDownloaded(
            auth()->user(),
            'pdf',
            "convocation-{$convocation->qr_code}.pdf",
            'convocation_download'
        ));

        AuditLog::create([
            'event' => 'convocation_downloaded',
            'auditable_type' => Convocation::class,
            'auditable_id' => $convocation->id,
            'user_id' => auth()->id(),
            'description' => "Convocation téléchargée par " . auth()->user()->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return Storage::download($convocation->pdf_url, "convocation-{$convocation->qr_code}.pdf");
    }

    public function send(Request $request)
    {
        $this->authorize('generate convocations');

        $request->validate([
            'convocation_ids' => 'required|array',
            'convocation_ids.*' => 'exists:convocations,id',
            'channels' => 'required|array',
            'channels.*' => 'in:email,sms,in_app',
        ]);

        $sent = 0;

        foreach ($request->convocation_ids as $id) {
            $convocation = Convocation::findOrFail($id);

            foreach ($request->channels as $channel) {
                $this->sendViaChannel($convocation, $channel);
            }

            $convocation->update(['sent_at' => now(), 'status' => 'sent']);
            $sent++;
        }

        AuditLog::create([
            'event' => 'convocations_sent',
            'auditable_type' => Convocation::class,
            'auditable_id' => $convocation->id ?? null, // On logge l'action globale
            'user_id' => auth()->id(),
            'description' => "{$sent} convocations envoyées via " . implode(', ', $request->channels),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', "{$sent} convocations envoyées avec succès.");
    }

    private function sendViaChannel(Convocation $convocation, string $channel)
    {
        $student = $convocation->student;
        $session = $convocation->examSession;

        switch ($channel) {
            case 'email':
                // Envoyer email avec convocation
                \Illuminate\Support\Facades\Mail::to($student->email)->queue(new \App\Mail\ConvocationMail($convocation));
                break;

            case 'sms':
                // Envoyer SMS avec notification
                $smsService = app(\App\Services\SmsService::class);
                $message = "EduPass: Votre convocation pour l'examen {$session->type} du {$session->date->format('d/m/Y')} est disponible. Telechargez-la sur votre espace etudiant.";
                $smsService->send($student->phone, $message);
                break;

            case 'in_app':
                // Notification in-app
                if (class_exists(\App\Notifications\ConvocationReady::class)) {
                    $student->user->notify(new \App\Notifications\ConvocationReady($convocation));
                }
                break;
        }
    }


    public function regenerate(Convocation $convocation)
    {
        $this->authorize('generate convocations');

        // Supprimer l'ancien PDF
        if ($convocation->pdf_url && Storage::exists($convocation->pdf_url)) {
            Storage::delete($convocation->pdf_url);
        }

        // Régénérer
        $this->generatePDF($convocation);

        return back()->with('success', 'Convocation régénérée avec succès.');
    }

    public function bulkGenerate(Request $request)
    {
        $this->authorize('generate convocations');

        $request->validate([
            'exam_session_id' => 'required|exists:exam_sessions,id',
            'generate_for' => 'required|in:all,paid',
        ]);

        $session = ExamSession::findOrFail($request->exam_session_id);

        $studentsQuery = Student::where('status', 'active');

        if ($request->generate_for === 'paid') {
            // Seulement les étudiants ayant payé
            $studentsQuery->whereHas('user.payments', function ($query) {
                $query->where('status', 'paid');
            });
        }

        $students = $studentsQuery->get();
        $generated = 0;

        foreach ($students as $student) {
            // Vérifier si convocation existe déjà
            if (
                Convocation::where('student_id', $student->id)
                    ->where('exam_session_id', $session->id)
                    ->exists()
            ) {
                continue;
            }

            $qrCode = Str::uuid()->toString();

            $convocation = Convocation::create([
                'student_id' => $student->id,
                'exam_session_id' => $session->id,
                'qr_code' => $qrCode,
                'status' => 'generated',
            ]);

            $this->generatePDF($convocation);
            $generated++;
        }

        return back()->with('success', "{$generated} convocations générées en masse.");
    }
}
