<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #10b981;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #059669;
            margin: 0 0 10px 0;
            font-size: 24px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin: 10px 0;
        }

        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .amount-box {
            text-align: center;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 2px solid #10b981;
            padding: 25px;
            margin: 25px 0;
            border-radius: 8px;
        }

        .amount-box .label {
            font-size: 14px;
            color: #64748b;
            margin: 0;
        }

        .amount-box .amount {
            font-size: 36px;
            font-weight: 700;
            color: #059669;
            margin: 10px 0;
        }

        .amount-box .type {
            font-size: 13px;
            color: #475569;
            margin: 0;
        }

        .info-box {
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .info-row {
            margin: 10px 0;
        }

        .label {
            font-weight: 600;
            color: #475569;
            display: inline-block;
            min-width: 150px;
        }

        .value {
            color: #0f172a;
        }

        .cta-button {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff !important;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }

        .cta-button:hover {
            background-color: #059669;
        }

        .success-icon {
            font-size: 48px;
            margin: 10px 0;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid: #e2e8f0;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }

        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 20px;
            }

            .label {
                display: block;
                margin-bottom: 5px;
            }

            .amount-box .amount {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">✅</div>
            <h1>PAIEMENT CONFIRMÉ</h1>
            <p>Centre National de Télé-Enseignement de Madagascar</p>
            <span class="status-badge status-{{ $payment->status === 'paid' ? 'paid' : 'pending' }}">
                {{ $payment->status === 'paid' ? 'PAYÉ' : 'EN ATTENTE' }}
            </span>
        </div>

        <div class="content">
            <p>Bonjour <strong>{{ $payment->user->name }}</strong>,</p>

            @if($payment->status === 'paid')
                <p>Votre paiement a été <strong>confirmé avec succès</strong>. Merci pour votre confiance !</p>
            @else
                <p>Nous avons bien reçu votre demande de paiement. Elle est en cours de traitement.</p>
            @endif

            <div class="amount-box">
                <p class="label">Montant payé</p>
                <div class="amount">{{ number_format($payment->amount, 0, ',', ' ') }} Ar</div>
                <p class="type">{{ ucfirst($payment->type ?? 'Paiement') }}</p>
            </div>

            <div class="info-box">
                <h3 style="margin-top: 0; color: #059669;">💳 Détails de la Transaction</h3>
                <div class="info-row">
                    <span class="label">N° de transaction :</span>
                    <span class="value"><strong>{{ $payment->transaction_id }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="label">Date de paiement :</span>
                    <span class="value">{{ $payment->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                @if($payment->paid_at)
                    <div class="info-row">
                        <span class="label">Confirmé le :</span>
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

            <div class="info-box" style="background-color: #f1f5f9; border-left-color: #2563eb;">
                <h3 style="margin-top: 0; color: #1e40af;">👤 Informations Étudiant</h3>
                <div class="info-row">
                    <span class="label">Nom :</span>
                    <span class="value">{{ $payment->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email :</span>
                    <span class="value">{{ $payment->user->email }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('payments.receipt', $payment) }}" class="cta-button">
                    📥 Télécharger le reçu PDF
                </a>
                <p style="font-size: 12px; color: #64748b; margin-top: 10px;">
                    Ou consultez votre historique : <a href="{{ route('payments.history') }}"
                        style="color: #10b981;">Mes paiements</a>
                </p>
            </div>

            @if($payment->status === 'paid')
                <div class="info-box" style="background-color: #fef3c7; border-left-color: #f59e0b;">
                    <h4 style="margin-top: 0; color: #92400e;">📌 Prochaines Étapes</h4>
                    <p style="margin: 5px 0; font-size: 14px; color: #78350f;">
                        • Conservez ce reçu comme preuve de paiement<br>
                        • Votre convocation sera disponible prochainement<br>
                        • Vous recevrez une notification par email et SMS
                    </p>
                </div>
            @endif
        </div>

        <div class="footer">
            <p><strong>EduPass Madagascar</strong></p>
            <p>Centre National de Télé-Enseignement de Madagascar (CNTEMAD)</p>
            <p style="margin-top: 10px;">
                Des questions ? Contactez-nous :
                <a href="mailto:contact@edupass.mg" style="color: #10b981;">contact@edupass.mg</a>
            </p>
            <p style="margin-top: 15px; font-size: 11px; color: #94a3b8;">
                Ce reçu est valide sans signature ni cachet.<br>
                Cet email a été envoyé automatiquement, merci de ne pas y répondre.<br>
                © {{ date('Y') }} EduPass Madagascar - Tous droits réservés
            </p>
        </div>
    </div>
</body>

</html>