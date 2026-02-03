<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>En attente de validation - EduPass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        mvola: { 500: '#00a651', 600: '#008c44' },
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

<body class="min-h-screen flex items-center justify-center p-4 text-white">

    <!-- Background Image -->
    <div class="fixed inset-0 z-0">
        <img src="/images/premium-bg.png" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-premium-dark/40 backdrop-blur-[2px]"></div>
    </div>

    <div class="max-w-sm w-full space-y-8 relative z-10">
        <!-- Status Card -->
        <div
            class="glass rounded-3xl p-5 text-center shadow-2xl relative overflow-hidden">
            <div
                class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-500 via-red-500 to-green-500 animate-pulse">
            </div>

            <div class="w-16 h-16 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-4 relative">
                <div class="absolute inset-0 border-4 border-indigo-500/30 rounded-full animate-ping"></div>
                <div class="absolute inset-0 border-4 border-t-indigo-500 rounded-full animate-spin"></div>
                <i class="fas fa-mobile-alt text-3xl text-indigo-400"></i>
            </div>

            <h1 class="text-xl font-bold mb-1">Vérifiez votre téléphone</h1>
            <p class="text-slate-400 mb-4 text-sm">Une demande de paiement a été envoyée au <span
                    class="text-white font-mono">{{ $payment->phone }}</span></p>

            <div class="bg-black/30 rounded-xl p-3 mb-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-slate-400">Montant</span>
                    <span class="font-bold">{{ number_format($payment->amount, 0, ',', ' ') }} Ar</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Référence</span>
                    <span class="font-mono text-xs">{{ $payment->transaction_id }}</span>
                </div>
            </div>

            <div class="flex items-center justify-center gap-2 text-xs text-slate-500">
                <i class="fas fa-sync fa-spin"></i>
                En attente de validation...
            </div>
        </div>
    </div>

    <script>
        // Polling pour vérifier le statut
        setInterval(function () {
            fetch("{{ route('payment.check-status', $payment->id) }}")
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'paid') {
                        window.location.href = "{{ route('payment.success') }}";
                    }
                });
        }, 3000);
    </script>
</body>

</html>