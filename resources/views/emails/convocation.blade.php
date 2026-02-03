<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Votre Convocation EduPass</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4F46E5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Convocation Disponible</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $studentName }},</p>
            <p>Votre convocation pour la session d'examen <strong>{{ $sessionType }}</strong> du
                <strong>{{ $sessionDate }}</strong> est maintenant disponible.</p>
            <p>Vous pouvez la télécharger directement en cliquant sur le bouton ci-dessous :</p>
            <center>
                <a href="{{ $downloadUrl }}" class="button">Télécharger ma Convocation</a>
            </center>
            <p>Merci de vous présenter muni de cette convocation et de votre pièce d'identité.</p>
            <p>Cordialement,<br>L'équipe EduPass</p>
        </div>
        <div class="footer">
            <p>Ceci est un message automatique, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>

</html>