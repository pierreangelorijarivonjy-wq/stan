<?php

namespace App\Services;

use App\Models\Convocation;
use App\Models\Student;
use App\Models\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;  // ←←← CETTE LIGNE MANQUAIT !

class ConvocationService
{
    public function generateConvocation($studentId, $sessionId)
    {
        $student = Student::findOrFail($studentId);
        $session = Session::findOrFail($sessionId);

        $uniqueCode = Str::uuid();
        $qrData = route('verification.verify', $uniqueCode);

        // Générer QR code
        $qrCode = QrCode::create($qrData)
            ->setSize(300)
            ->setMargin(10);

        $writer = new PngWriter();
        $qrResult = $writer->write($qrCode);
        $qrBase64 = 'data:image/png;base64,' . base64_encode($qrResult->getString());

        // Générer PDF
        $pdf = Pdf::loadView('pdf.convocation', [
            'student' => $student,
            'session' => $session,
            'qrBase64' => $qrBase64,
            'code' => $uniqueCode,
        ]);

        // Pour MVP : hash simple comme signature
        $pdfContent = $pdf->output();
        $signature = hash('sha256', $pdfContent);

        // Sauvegarde fichier
        $filename = 'convocation_' . $student->matricule . '_' . $session->id . '.pdf';
        $path = 'convocations/' . $filename;
        Storage::disk('public')->put($path, $pdfContent); // ← Plus propre sans \

        // Créer enregistrement
        $convocation = Convocation::create([
            'student_id' => $student->id,
            'session_id' => $session->id,
            'pdf_url' => Storage::url($path), // ← Aussi ici
            'qr_code_data' => $uniqueCode,
            'signature_numerique' => $signature,
            'statut' => 'generee',
        ]);

        return $convocation;
    }
}