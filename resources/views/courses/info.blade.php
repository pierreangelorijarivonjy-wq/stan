<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informatique Fondamentale - Verrouillé</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gradient-to-br from-red-50 to-pink-50 min-h-screen p-8">
    <div class="max-w-4xl mx-auto text-center">
        <a href="{{ route('home') }}"
            class="inline-flex items-center text-red-600 hover:text-red-800 font-bold mb-8 text-xl">
            <i class="fas fa-chevron-left mr-2"></i> Retour au catalogue
        </a>

        <div class="bg-white rounded-3xl shadow-2xl p-16">
            <i class="fas fa-lock text-red-500 text-9xl mb-8"></i>
            <h1 class="text-5xl font-black text-red-600 mb-8">Cours Verrouillé</h1>
            <p class="text-2xl text-gray-700 mb-12">
                Paiement requis pour accéder au contenu complet d'Informatique Fondamentale
            </p>
            <p class="text-xl text-gray-600 mb-12">
                40 vidéos • 200 exercices • Projets pratiques • Certificat à la fin
            </p>
            <p class="text-5xl font-black text-red-600 mb-12">50 000 Ar</p>

            <a href="{{ route('test.form') }}"
                class="inline-block bg-red-600 hover:bg-red-700 text-white font-black text-2xl px-16 py-8 rounded-3xl transition transform hover:scale-105 shadow-2xl">
                Payer avec Mobile Money (USSD)
            </a>
        </div>
    </div>
</body>

</html>