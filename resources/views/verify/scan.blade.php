<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner QR - EduPass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-4">Scanner QR Code</h1>

                <!-- Scanner -->
                <div id="reader" class="w-full mb-4"></div>

                <!-- Résultat -->
                <div id="result" class="hidden mt-6"></div>

                <!-- Bouton retour -->
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}"
                        class="inline-block bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                        ← Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const resultDiv = document.getElementById('result');

        function onScanSuccess(decodedText, decodedResult) {
            // Arrêter le scanner
            html5QrcodeScanner.clear();

            // Vérifier le code
            fetch('/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: decodedText })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        if (data.type === 'convocation') {
                            resultDiv.innerHTML = `
                        <div class="bg-green-100 border-2 border-green-500 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <span class="text-4xl mr-3">✅</span>
                                <h3 class="text-2xl font-bold text-green-800">Convocation Valide</h3>
                            </div>
                            <div class="space-y-2">
                                <p><strong>Étudiant:</strong> ${data.data.student_name}</p>
                                <p><strong>Matricule:</strong> ${data.data.matricule}</p>
                                <p><strong>Session:</strong> ${data.data.session_type}</p>
                                <p><strong>Date:</strong> ${data.data.session_date} à ${data.data.session_time}</p>
                                <p><strong>Centre:</strong> ${data.data.center}</p>
                                ${data.data.room ? `<p><strong>Salle:</strong> ${data.data.room}</p>` : ''}
                            </div>
                        </div>
                    `;
                        } else {
                            resultDiv.innerHTML = `
                        <div class="bg-green-100 border-2 border-green-500 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <span class="text-4xl mr-3">✅</span>
                                <h3 class="text-2xl font-bold text-green-800">Paiement Valide</h3>
                            </div>
                            <div class="space-y-2">
                                <p><strong>Étudiant:</strong> ${data.data.student_name}</p>
                                <p><strong>Montant:</strong> ${data.data.amount}</p>
                                <p><strong>Statut:</strong> ${data.data.status}</p>
                            </div>
                        </div>
                    `;
                        }
                    } else {
                        resultDiv.innerHTML = `
                    <div class="bg-red-100 border-2 border-red-500 rounded-lg p-6">
                        <div class="flex items-center">
                            <span class="text-4xl mr-3">❌</span>
                            <div>
                                <h3 class="text-2xl font-bold text-red-800">Invalide</h3>
                                <p class="text-red-700">${data.message}</p>
                            </div>
                        </div>
                    </div>
                `;
                    }
                    resultDiv.classList.remove('hidden');

                    // Permettre un nouveau scan après 3 secondes
                    setTimeout(() => {
                        resultDiv.classList.add('hidden');
                        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                    }, 5000);
                })
                .catch(error => {
                    resultDiv.innerHTML = `
                <div class="bg-red-100 border-2 border-red-500 rounded-lg p-6">
                    <p class="text-red-800">Erreur de vérification</p>
                </div>
            `;
                    resultDiv.classList.remove('hidden');
                });
        }

        function onScanFailure(error) {
            // Ignorer les erreurs de scan (trop fréquentes)
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: { width: 250, height: 250 } },
            false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</body>

</html>