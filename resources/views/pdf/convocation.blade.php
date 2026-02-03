<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convocation - {{ $student->matricule }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #1e40af;
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #64748b;
        }

        .content {
            margin: 20px 0;
        }

        .info-section {
            margin: 20px 0;
            padding: 15px;
            background: #f1f5f9;
            border-left: 4px solid #2563eb;
        }

        .info-row {
            margin: 8px 0;
        }

        .label {
            font-weight: bold;
            color: #334155;
            display: inline-block;
            width: 150px;
        }

        .value {
            color: #0f172a;
        }

        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 2px dashed #cbd5e1;
        }

        .qr-section img {
            margin: 10px 0;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }

        .important {
            background: #fef3c7;
            border: 1px solid #fbbf24;
            padding: 10px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .signature-box {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>CONVOCATION OFFICIELLE</h1>
        <p>Centre National de Télé-Enseignement de Madagascar</p>
        <p>{{ $session->center }}</p>
    </div>

    <div class="content">
        <h2 style="color: #1e40af;">Informations Étudiant</h2>
        <div class="info-section">
            <div class="info-row">
                <span class="label">Nom complet :</span>
                <span class="value">{{ $student->first_name }} {{ $student->last_name }}</span>
            </div>
            <div class="info-row">
                <span class="label">Matricule :</span>
                <span class="value">{{ $student->matricule }}</span>
            </div>
            <div class="info-row">
                <span class="label">Email :</span>
                <span class="value">{{ $student->email }}</span>
            </div>
            <div class="info-row">
                <span class="label">Téléphone :</span>
                <span class="value">{{ $student->phone }}</span>
            </div>
        </div>

        <h2 style="color: #1e40af;">Détails de la Session</h2>
        <div class="info-section">
            <div class="info-row">
                <span class="label">Type :</span>
                <span class="value">{{ strtoupper($session->type) }}</span>
            </div>
            <div class="info-row">
                <span class="label">Date :</span>
                <span class="value">{{ $session->date->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Heure :</span>
                <span class="value">{{ $session->time }}</span>
            </div>
            <div class="info-row">
                <span class="label">Centre :</span>
                <span class="value">{{ $session->center }}</span>
            </div>
            @if($session->room)
                <div class="info-row">
                    <span class="label">Salle :</span>
                    <span class="value">{{ $session->room }}</span>
                </div>
            @endif
        </div>

        <div class="important">
            <strong>⚠️ IMPORTANT :</strong>
            <ul style="margin: 5px 0; padding-left: 20px;">
                <li>Présentez-vous 30 minutes avant l'heure indiquée</li>
                <!DOCTYPE html>
                <html lang="fr">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Convocation - {{ $student->matricule }}</title>
                    <style>
                        body {
                            font-family: 'DejaVu Sans', Arial, sans-serif;
                            margin: 0;
                            padding: 20px;
                            font-size: 12px;
                        }

                        .header {
                            text-align: center;
                            border-bottom: 3px solid #2563eb;
                            padding-bottom: 15px;
                            margin-bottom: 30px;
                        }

                        .header h1 {
                            color: #1e40af;
                            margin: 0;
                            font-size: 24px;
                        }

                        .header p {
                            margin: 5px 0;
                            color: #64748b;
                        }

                        .content {
                            margin: 20px 0;
                        }

                        .info-section {
                            margin: 20px 0;
                            padding: 15px;
                            background: #f1f5f9;
                            border-left: 4px solid #2563eb;
                        }

                        .info-row {
                            margin: 8px 0;
                        }

                        .label {
                            font-weight: bold;
                            color: #334155;
                            display: inline-block;
                            width: 150px;
                        }

                        .value {
                            color: #0f172a;
                        }

                        .qr-section {
                            text-align: center;
                            margin: 30px 0;
                            padding: 20px;
                            border: 2px dashed #cbd5e1;
                        }

                        .qr-section img {
                            margin: 10px 0;
                        }

                        .footer {
                            margin-top: 40px;
                            padding-top: 20px;
                            border-top: 1px solid #e2e8f0;
                            text-align: center;
                            font-size: 10px;
                            color: #64748b;
                        }

                        .important {
                            background: #fef3c7;
                            border: 1px solid #fbbf24;
                            padding: 10px;
                            margin: 20px 0;
                            border-radius: 4px;
                        }

                        .signature-box {
                            margin-top: 40px;
                            text-align: right;
                        }
                    </style>
                </head>

                <body>
                    <div class="header">
                        <h1>CONVOCATION OFFICIELLE</h1>
                        <p>Centre National de Télé-Enseignement de Madagascar</p>
                        <p>{{ $session->center }}</p>
                    </div>

                    <div class="content">
                        <h2 style="color: #1e40af;">Informations Étudiant</h2>
                        <div class="info-section">
                            <div class="info-row">
                                <span class="label">Nom complet :</span>
                                <span class="value">{{ $student->first_name }} {{ $student->last_name }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Matricule :</span>
                                <span class="value">{{ $student->matricule }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Email :</span>
                                <span class="value">{{ $student->email }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Téléphone :</span>
                                <span class="value">{{ $student->phone }}</span>
                            </div>
                        </div>

                        <h2 style="color: #1e40af;">Détails de la Session</h2>
                        <div class="info-section">
                            <div class="info-row">
                                <span class="label">Type :</span>
                                <span class="value">{{ strtoupper($session->type) }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Date :</span>
                                <span class="value">{{ $session->date->format('d/m/Y') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Heure :</span>
                                <span class="value">{{ $session->time }}</span>
                            </div>
                            <div class="info-row">
                                <span class="label">Centre :</span>
                                <span class="value">{{ $session->center }}</span>
                            </div>
                            @if($session->room)
                                <div class="info-row">
                                    <span class="label">Salle :</span>
                                    <span class="value">{{ $session->room }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="important">
                            <strong>⚠️ IMPORTANT :</strong>
                            <ul style="margin: 5px 0; padding-left: 20px;">
                                <li>Présentez-vous 30 minutes avant l'heure indiquée</li>
                                <li>Munissez-vous de votre pièce d'identité originale</li>
                                <li>Cette convocation est strictement personnelle et non transférable</li>
                                <li>Le QR code ci-dessous sera scanné à l'entrée pour vérification</li>
                            </ul>
                        </div>

                        <div class="qr-section">
                            <h3 style="margin: 0 0 10px 0; color: #475569;">Code de Vérification</h3>
                            <img src="data:image/png;base64,{{ $qrCodeImage }}" alt="QR Code" width="150" height="150">
                            <p style="margin: 10px 0; font-size: 10px; color: #64748b;">
                                Code: {{ $convocation->qr_code }}
                            </p>
                            <p style="margin: 0; font-size: 8px; color: #94a3b8; font-family: monospace;">
                                SIG: {{ $signature }}
                            </p>
                        </div>

                        <div class="signature-box">
                            <p style="margin: 5px 0;">Le Directeur du CNTEMAD</p>
                            <p style="margin: 5px 0; font-style: italic; color: #64748b;">Certifié numériquement</p>
                        </div>
                    </div>

                    <div class="footer">
                        <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
                        <p style="font-weight: bold; color: #1e40af;">Cette convocation est certifiée numériquement et
                            infalsifiable</p>
                        <p>© {{ date('Y') }} EduPass-MG - Excellence Éducative</p>
                    </div>
                </body>

                </html>