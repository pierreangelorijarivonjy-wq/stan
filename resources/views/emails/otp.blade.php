<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de vérification EduPass</title>
    <style>
        body { font-family: 'Inter', -apple-system, sans-serif; line-height: 1.6; color: #1e293b; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { background-color: #f8fafc; padding: 40px 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .header { background: #3b82f6; color: #ffffff; padding: 40px 30px; text-align: center; }
        .header-title { font-size: 28px; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 12px; margin: 0; }
        .content { padding: 40px 35px; }
        .greeting { font-size: 20px; font-weight: 700; margin-bottom: 20px; }
        .main-text { font-size: 16px; color: #475569; margin-bottom: 30px; }
        .otp-container { background: #eff6ff; border: 2px solid #bfdbfe; border-radius: 16px; padding: 35px; text-align: center; margin: 30px 0; }
        .otp-label { color: #64748b; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; }
        .otp-code { font-size: 56px; font-weight: 900; color: #1e3a8a; letter-spacing: 12px; margin: 0; }
        .alert-box { border-radius: 12px; padding: 18px; display: flex; align-items: start; gap: 12px; margin-bottom: 15px; }
        .alert-expire { background: #fffbeb; border-left: 4px solid #f59e0b; color: #92400e; }
        .alert-security { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }
        .alert-text { font-size: 14px; margin: 0; }
        .footer { text-align: center; padding: 30px; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header matching photo -->
            <div class="header">
                <h1 class="header-title">🎓 EduPass Madagascar</h1>
            </div>

            <div class="content">
                <p class="greeting">Bonjour <strong>{{ $user->name }}</strong>,</p>
                <p class="main-text">Vous avez demandé un code de vérification pour accéder à votre compte EduPass.</p>

                <!-- OTP Box matching photo -->
                <div class="otp-container">
                    <p class="otp-label">VOTRE CODE DE VÉRIFICATION</p>
                    <p class="otp-code">{{ $otpCode }}</p>
                </div>

                <!-- Expire alert matching photo style -->
                <div class="alert-box alert-expire">
                    <p class="alert-text">⏱️ <strong>Ce code expire dans 3 minutes.</strong></p>
                </div>

                <p class="main-text" style="margin-top: 25px;">Saisissez ce code sur la page de vérification pour accéder à votre tableau de bord.</p>

                <!-- Security alert matching photo style -->
                <div class="alert-box alert-security">
                    <p class="alert-text">🔒 <strong>Note de sécurité :</strong> Ne partagez jamais ce code avec qui que ce soit. L'équipe EduPass ne vous demandera jamais votre code de vérification.</p>
                </div>
            </div>

            <div class="footer">
                <p>© {{ date('Y') }} EduPass Madagascar. Tous droits réservés.</p>
                <p>Si vous n'avez pas demandé ce code, vous pouvez ignorer cet email en toute sécurité.</p>
            </div>
        </div>
    </div>
</body>
</html>