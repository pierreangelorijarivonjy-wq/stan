<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ExamSession;
use App\Models\Result;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\AuditLog;

class TranscriptController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_session_id' => 'required|exists:exam_sessions,id',
        ]);

        $student = Student::findOrFail($request->student_id);
        $session = ExamSession::findOrFail($request->exam_session_id);
        $results = Result::where('student_id', $student->id)
            ->where('exam_session_id', $session->id)
            ->where('status', 'published')
            ->get();

        if ($results->isEmpty()) {
            return back()->with('error', 'Aucun résultat publié pour cet étudiant dans cette session.');
        }

        $average = $results->avg('grade');
        $timestamp = now()->toDateTimeString();
        $qrCodeValue = Str::uuid()->toString();

        // Signature numérique
        $signatureData = [
            'student_id' => $student->id,
            'session_id' => $session->id,
            'average' => $average,
            'qr_code' => $qrCodeValue,
            'secret' => config('app.key')
        ];
        $digitalSignature = hash_hmac('sha256', json_encode($signatureData), config('app.key'));

        // QR Code
        $verifyUrl = route('verify.transcript', [
            'code' => $qrCodeValue,
            'sig' => substr($digitalSignature, 0, 16)
        ]);

        $qrCode = \Endroid\QrCode\QrCode::create($verifyUrl)
            ->setSize(200)
            ->setMargin(10);

        $writer = new \Endroid\QrCode\Writer\PngWriter();
        $result = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        // PDF
        $pdf = PDF::loadView('pdf.transcript', [
            'student' => $student,
            'session' => $session,
            'results' => $results,
            'average' => $average,
            'qrCodeImage' => $qrCodeBase64,
            'timestamp' => $timestamp,
            'signature' => $digitalSignature,
            'code' => $qrCodeValue
        ]);

        // Sécuriser le PDF avec un mot de passe (le matricule de l'étudiant)
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        if (method_exists($canvas, 'get_cpdf')) {
            $canvas->get_cpdf()->setEncryption($student->matricule, config('app.key'));
        }

        $path = "transcripts/{$qrCodeValue}.pdf";
        Storage::put($path, $pdf->output());

        // On pourrait stocker le chemin dans une table transcripts si besoin, 
        // ou simplement le servir directement.

        return $pdf->download("releve-notes-{$student->matricule}.pdf");
    }
}
