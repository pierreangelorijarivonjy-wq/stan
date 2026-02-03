<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé de Notes - {{ $student->matricule }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1e293b; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { color: #4f46e5; margin: 0; font-size: 22px; }
        .student-info { margin-bottom: 30px; background: #f8fafc; padding: 15px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #4f46e5; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .average-row { font-weight: bold; background: #f1f5f9; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #64748b; }
        .qr-section { float: right; text-align: center; margin-top: -20px; }
        .signature-section { margin-top: 40px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RELEVÉ DE NOTES OFFICIEL</h1>
        <p>EduPass-MG - Excellence Académique</p>
    </div>

    <div class="student-info">
        <table style="margin-bottom: 0; background: transparent;">
            <tr>
                <td style="border: none; width: 50%;">
                    <strong>Étudiant :</strong> {{ $student->first_name }} {{ $student->last_name }}<br>
                    <strong>Matricule :</strong> {{ $student->matricule }}<br>
                    <strong>Email :</strong> {{ $student->email }}
                </td>
                <td style="border: none; width: 50%;">
                    <strong>Session :</strong> {{ strtoupper($session->type) }}<br>
                    <strong>Date :</strong> {{ $session->date->format('d/m/Y') }}<br>
                    <strong>Centre :</strong> {{ $session->center }}
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Matière</th>
                <th style="text-align: center; width: 100px;">Note / 20</th>
                <th style="text-align: center; width: 150px;">Mention</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
            <tr>
                <td>{{ $result->subject }}</td>
                <td style="text-align: center;">{{ number_format($result->grade, 2) }}</td>
                <td style="text-align: center;">
                    @if($result->grade >= 16) Très Bien
                    @elseif($result->grade >= 14) Bien
                    @elseif($result->grade >= 12) Assez Bien
                    @elseif($result->grade >= 10) Passable
                    @else Ajourné
                    @endif
                </td>
            </tr>
            @endforeach
            <tr class="average-row">
                <td>MOYENNE GÉNÉRALE</td>
                <td style="text-align: center;">{{ number_format($average, 2) }}</td>
                <td style="text-align: center;">
                    {{ $average >= 10 ? 'ADMIS' : 'REFUSÉ' }}
                </td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="qr-section">
            <img src="data:image/png;base64,{{ $qrCodeImage }}" width="120" height="120">
            <p style="font-size: 8px; font-family: monospace; margin-top: 5px;">SIG: {{ substr($signature, 0, 16) }}...</p>
        </div>
        
        <div style="margin-top: 40px;">
            <p>Fait à Antananarivo, le {{ date('d/m/Y') }}</p>
            <p><strong>Le Recteur</strong></p>
            <p style="font-style: italic; color: #64748b; margin-top: 40px;">Document certifié numériquement</p>
        </div>
    </div>

    <div class="footer">
        <p>Ce relevé de notes est un document officiel certifié par EduPass-MG.</p>
        <p>L'authenticité de ce document peut être vérifiée en scannant le QR code ci-dessus.</p>
        <p>Code de vérification : {{ $code }}</p>
    </div>
</body>
</html>
