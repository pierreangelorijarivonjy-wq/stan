<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paiement confirmé – EduPass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        premium: {
                            dark: '#0A0B3B'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(10, 11, 59, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Background Image -->
    <div class="fixed inset-0 z-0">
        <img src="/images/premium-bg.png" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-premium-dark/40 backdrop-blur-[2px]"></div>
    </div>

    <div class="max-w-xl text-center px-4 py-12 relative z-10">

        <div
            class="w-24 h-24 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl shadow-green-500/20">
            <i class="fas fa-check text-5xl text-white"></i>
        </div>

        <div class="glass p-8 rounded-[3rem] shadow-2xl">
            <h1 class="text-3xl font-black text-white mb-4">Paiement confirmé !</h1>

            <p class="text-lg text-blue-100 mb-8">
                Votre paiement a été reçu avec succès.<br>
                Votre convocation est maintenant disponible.
            </p>

            <a href="{{ route('dashboard') }}" class="inline-block px-10 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 
                  text-white font-black text-lg rounded-2xl shadow-2xl transform hover:scale-105 transition-all">
                Retour au tableau de bord
            </a>
        </div>
    </div>

</body>

</html>