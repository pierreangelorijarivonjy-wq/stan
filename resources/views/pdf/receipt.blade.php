<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Reçu de Paiement - {{ $payment->transaction_id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #10b981;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #059669;
            margin: 0;
            font-size: 24px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin: 10px 0;
        }

        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .info-section {
            margin: 20px 0;
            padding: 15px;
            background: #f0fdf4;
            border-left: 4px solid #10b981;
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

        .amount-box {
            text-align: center;
            padding: 20px;
            background: #ecfdf5;
            border: 2px solid #10b981;
            margin: 20px 0;
            border-radius: 8px;
        }

        .amount-box .amount {
            font-size: 32px;
            font-weight: bold;
            color: #059669;
            margin: 10px 0;
        }

        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 2px dashed #cbd5e1;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>REÇU DE PAIEMENT</h1>
        <p>Centre National de Télé-Enseignement de Madagascar</p>
        <span class="status-badge status-{{ $payment->status === 'paid' ? 'paid' : 'pending' }}">
            {{ strtoupper($payment->status) }}
        </span>
    </div>

    <div class="amount-box">
        <p style="margin: 0; color: #64748b;">Montant Payé</p>
        <div class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} Ar</div>
        <p style="margin: 0; font-size: 11px; color: #64748b;">
            {{ ucfirst($payment->type) }}
        </p>
    </div>

    <div class="info-section">
        <h3 style="margin: 0 0 10px 0; color: #059669;">Informations de Transaction</h3>
        <div class="info-row">
            <span class="label">N° Transaction :</span>
            <span class="value">{{ $payment->transaction_id }}</span>
        </div>
        <div class="info-row">
            <span class="label">Date :</span>
            <span class="value">{{ $payment->created_at->format('d/m/Y à H:i') }}</span>
        </div>
        @if($payment->paid_at)
            <div class="info-row">
                <span class="label">Payé le :</span>
                <span class="value">{{ $payment->paid_at->format('d/m/Y à H:i') }}</span>
            </div>
        @endif
        <div class="info-row">
            <span class="label">Méthode :</span>
            <span class="value">{{ strtoupper($payment->provider) }} ({{ $payment->method ?? 'Mobile Money' }})</span>
        </div>
        <div class="info-row">
            <span class="label">Téléphone :</span>
            <span class="value">{{ $payment->phone }}</span>
        </div>
    </div>

    <div class="info-section">
        <h3 style="margin: 0 0 10px 0; color: #059669;">Informations Étudiant</h3>
        <div class="info-row">
            <span class="label">Nom :</span>
            <span class="value">{{ $payment->user->name }}</span>
        </div>
        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <title>Reçu de Paiement - {{ $payment->transaction_id }}</title>
            <style>
                body {
                    font-family: 'DejaVu Sans', Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    font-size: 12px;
                }

                .header {
                    text-align: center;
                    border-bottom: 3px solid #10b981;
                    padding-bottom: 15px;
                    margin-bottom: 30px;
                }

                .header h1 {
                    color: #059669;
                    margin: 0;
                    font-size: 24px;
                }

                .status-badge {
                    display: inline-block;
                    padding: 5px 15px;
                    border-radius: 20px;
                    font-weight: bold;
                    margin: 10px 0;
                }

                .status-paid {
                    background: #d1fae5;
                    color: #065f46;
                }

                .status-pending {
                    background: #fef3c7;
                    color: #92400e;
                }

                .info-section {
                    margin: 20px 0;
                    padding: 15px;
                    background: #f0fdf4;
                    border-left: 4px solid #10b981;
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

                .amount-box {
                    text-align: center;
                    padding: 20px;
                    background: #ecfdf5;
                    border: 2px solid #10b981;
                    margin: 20px 0;
                    border-radius: 8px;
                }

                .amount-box .amount {
                    font-size: 32px;
                    font-weight: bold;
                    color: #059669;
                    margin: 10px 0;
                }

                .qr-section {
                    text-align: center;
                    margin: 30px 0;
                    padding: 20px;
                    border: 2px dashed #cbd5e1;
                }

                .footer {
                    margin-top: 40px;
                    padding-top: 20px;
                    border-top: 1px solid #e2e8f0;
                    text-align: center;
                    font-size: 10px;
                    color: #64748b;
                }
            </style>
        </head>

        <body>
            <div class="header">
                <h1>REÇU DE PAIEMENT</h1>
                <p>Centre National de Télé-Enseignement de Madagascar</p>
                <span class="status-badge status-{{ $payment->status === 'paid' ? 'paid' : 'pending' }}">
                    {{ strtoupper($payment->status) }}
                </span>
            </div>

            <div class="amount-box">
                <p style="margin: 0; color: #64748b;">Montant Payé</p>
                <div class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} Ar</div>
                <p style="margin: 0; font-size: 11px; color: #64748b;">
                    {{ ucfirst($payment->type) }}
                </p>
            </div>

            <div class="info-section">
                <h3 style="margin: 0 0 10px 0; color: #059669;">Informations de Transaction</h3>
                <div class="info-row">
                    <span class="label">N° Transaction :</span>
                    <span class="value">{{ $payment->transaction_id }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Date :</span>
                    <span class="value">{{ $payment->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                @if($payment->paid_at)
                    <div class="info-row">
                        <span class="label">Payé le :</span>
                        <span class="value">{{ $payment->paid_at->format('d/m/Y à H:i') }}</span>
                    </div>
                @endif
                <div class="info-row">
                    <span class="label">Méthode :</span>
                    <span class="value">{{ strtoupper($payment->provider) }}
                        ({{ $payment->method ?? 'Mobile Money' }})</span>
                </div>
                <div class="info-row">
                    <span class="label">Téléphone :</span>
                    <span class="value">{{ $payment->phone }}</span>
                </div>
            </div>

            <div class="info-section">
                <h3 style="margin: 0 0 10px 0; color: #059669;">Informations Étudiant</h3>
                <div class="info-row">
                    <span class="label">Nom :</span>
                    <span class="value">{{ $payment->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email :</span>
                    <span class="value">{{ $payment->user->email }}</span>
                </div>
            </div>

            <div class="qr-section">
                <h3 style="margin: 0 0 10px 0; color: #475569;">Code de Vérification</h3>
                <img src="data:image/png;base64,{{ $qrCodeImage }}" alt="QR Code" width="150" height="150">
                <p style="margin: 10px 0; font-size: 10px; color: #64748b;">
                    Scannez pour vérifier l'authenticité
                </p>
                <p style="margin: 0; font-size: 8px; color: #94a3b8; font-family: monospace;">
                    SIG: {{ $signature }}
                </p>
            </div>

            <div class="footer">
                <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
                <p style="font-weight: bold; color: #059669;">Ce reçu est certifié numériquement et infalsifiable</p>
                <p>© {{ date('Y') }} EduPass-MG - Excellence Éducative</p>
            </div>
        </body>

        </html>