<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #334155;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #4f46e5;
            margin: 0;
        }

        .content {
            margin-bottom: 30px;
        }

        .details {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>EduPass-MG</h1>
        </div>
        <div class="content">
            <p>Bonjour,</p>
            <p>Notre service comptabilité a identifié une transaction bancaire qui semble vous appartenir mais qui
                nécessite un complément d'information pour être validée.</p>

            <div class="details">
                <p><strong>Détails de la transaction :</strong></p>
                <ul>
                    <li>Date : {{ $statement->date->format('d/m/Y') }}</li>
                    <li>Montant : {{ number_format($statement->amount, 0, ',', ' ') }} Ar</li>
                    <li>Référence : {{ $statement->reference }}</li>
                </ul>
            </div>

            <p><strong>Message du comptable :</strong></p>
            <p style="font-style: italic; color: #475569; border-left: 4px solid #e2e8f0; padding-left: 15px;">
                "{{ $customMessage }}"
            </p>

            <p>Veuillez vous connecter à votre espace étudiant pour soumettre votre preuve de paiement ou contacter le
                support si vous avez des questions.</p>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('payments') }}" class="btn">Accéder à mes paiements</a>
            </div>
        </div>
        <div class="footer">
            <p>Ceci est un message automatique, merci de ne pas y répondre directement.</p>
            <p>&copy; {{ date('Y') }} EduPass-MG - Excellence Éducative</p>
        </div>
    </div>
</body>

</html>