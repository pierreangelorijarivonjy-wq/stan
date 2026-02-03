<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Économie & Gestion - Cours Complet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gradient-to-br from-yellow-50 to-orange-50 min-h-screen p-8">
    <div class="max-w-6xl mx-auto">
        <a href="{{ route('home') }}"
            class="inline-flex items-center text-yellow-600 hover:text-yellow-800 font-bold mb-8 text-xl">
            <i class="fas fa-chevron-left mr-2"></i> Retour au catalogue
        </a>

        <div class="bg-white rounded-3xl shadow-2xl p-12">
            <h1 class="text-5xl font-black text-yellow-600 mb-8">Économie & Gestion</h1>
            <p class="text-xl text-gray-600 mb-12">
                Progression : 45% - Continue ton excellent travail !
            </p>

            <!-- Modules (seulement le module 1 déverrouillé) -->
            <div class="space-y-8">
                <div class="border-4 border-yellow-200 rounded-2xl p-6 bg-yellow-50">
                    <h2 class="text-3xl font-black mb-4">Module 1 : Principes de base</h2>
                    <p class="text-lg">Contenu complet disponible : PDF, vidéos, quiz, cas pratiques...</p>
                </div>

                <div class="border-4 border-dashed border-gray-300 rounded-2xl p-6 bg-gray-100 opacity-60">
                    <h2 class="text-3xl font-black mb-4">Module 2 : Macro-économie</h2>
                    <p class="text-lg text-gray-600">Verrouillé - Termine le module 1 pour débloquer</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>