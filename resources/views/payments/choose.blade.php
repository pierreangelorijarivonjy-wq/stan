<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paiement Sécurisé - EduPass-MG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://unpkg.com/imask"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Roboto', 'sans-serif'],
                        heading: ['Montserrat', 'sans-serif']
                    },
                    colors: {
                        'app-bg': '#F0F4F8',
                        'app-text': '#2D3748',
                        'mvola': '#00A859',
                        'orange-money': '#FF8C00',
                        'airtel-red': '#FF4D4D',
                        'card-blue': '#1A365D',
                        'pay-blue': '#3182CE',
                        'pay-blue-hover': '#2B6CB0',
                        'shield-blue': '#4299E1',
                        'error-red': '#E53E3E',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F0F4F8; color: #2D3748; }
        .payment-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 450px;
            max-height: 95vh;
            display: flex;
            flex-direction: column;
        }
        .method-btn {
            transition: all 0.2s ease;
            border: 1px solid #E2E8F0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #F7FAFC;
        }
        .method-btn:hover { border-color: #3182CE; transform: translateY(-1px); }
        .method-btn.active {
            border-color: #3182CE;
            background-color: #EBF8FF;
            box-shadow: 0 0 0 2px rgba(49, 130, 206, 0.2);
        }
        .input-field {
            height: 40px;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 0 12px;
            font-size: 14px;
            width: 100%;
            transition: all 0.2s ease;
        }
        .input-field:focus {
            outline: none;
            border-color: #3182CE;
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
        }
        .loader {
            border: 2px solid #f3f3f3;
            border-top: 2px solid white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
            display: none;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .tooltip {
            position: relative;
            display: inline-block;
        }
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: #2D3748;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 10px;
            line-height: 1.4;
        }
        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
        @media (max-height: 600px) {
            .payment-card { max-height: 90vh; overflow-y: auto; }
        }
        .method-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 font-sans antialiased">

    <div class="payment-card overflow-hidden">
        <!-- Header -->
        <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-white shrink-0">
            <a href="{{ route('payments') }}" class="text-slate-400 hover:text-app-text transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-[20px] font-bold text-app-text font-heading">Paiement Sécurisé</h1>
            <div class="tooltip">
                <i class="fas fa-shield-halved text-shield-blue text-lg cursor-help"></i>
                <span class="tooltiptext">Paiement sécurisé par HTTPS et chiffrement des données de bout en bout.</span>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 sm:p-6 space-y-6 overflow-y-auto">
            @if(session('error'))
                <div class="p-3 bg-red-50 border border-red-100 rounded text-error-red text-xs flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Total Display -->
            <div class="text-center py-2 shrink-0">
                <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest mb-1">Montant à régler</p>
                <p class="text-[18px] font-black text-app-text" id="display-amount">185 000.00 Ar</p>
            </div>

            <form action="{{ route('payment.initiate') }}" method="POST" id="payment-form" class="space-y-5" enctype="multipart/form-data">
                @csrf

                <!-- Fee Type -->
                <div class="space-y-1.5">
                    <label class="block text-[14px] font-bold text-slate-500">Type de frais</label>
                    <select name="type" id="fee-type" class="input-field appearance-none bg-white" onchange="updateAmount(this.value)">
                        <option value="inscription">Frais d'Inscription</option>
                        <option value="reinscription">Frais de Réinscription</option>
                        <option value="examen">Frais d'Examen</option>
                        <option value="scolarite" selected>Frais de Scolarité</option>
                    </select>
                    <input type="hidden" name="amount" id="amount-input" value="185000">
                </div>

                <!-- Payment Methods -->
                <div class="space-y-2">
                    <label class="block text-[14px] font-bold text-slate-500">Méthode de paiement</label>
                    <div class="method-row">
                        <button type="button" onclick="selectMethod('mvola')" id="btn-mvola" class="method-btn" title="MVola">
                            <img src="https://www.mvola.mg/wp-content/uploads/2023/03/Logo-MVola.png" alt="MVola" class="w-6 h-6 object-contain opacity-80" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <span class="text-[8px] font-bold hidden">MVola</span>
                        </button>
                        <button type="button" onclick="selectMethod('orange')" id="btn-orange" class="method-btn" title="Orange Money">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c8/Orange_logo.svg" alt="Orange" class="w-5 h-5 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <span class="text-[8px] font-bold hidden">Orange</span>
                        </button>
                        <button type="button" onclick="selectMethod('airtel')" id="btn-airtel" class="method-btn" title="Airtel Money">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/Airtel_logo-01.svg/1200px-Airtel_logo-01.svg.png" alt="Airtel" class="w-6 h-6 object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                            <span class="text-[8px] font-bold hidden">Airtel</span>
                        </button>
                        <button type="button" onclick="selectMethod('card')" id="btn-card" class="method-btn" title="Carte Bancaire">
                            <i class="fas fa-credit-card text-card-blue text-lg"></i>
                        </button>
                        <button type="button" onclick="selectMethod('transfer')" id="btn-transfer" class="method-btn" title="Virement Bancaire">
                            <i class="fas fa-university text-slate-600 text-lg"></i>
                        </button>
                    </div>
                    <input type="hidden" name="provider" id="provider-input" required>
                    <p id="provider-error" class="text-[10px] text-error-red hidden">Veuillez choisir une méthode.</p>
                </div>

                <!-- Dynamic Fields -->
                <div id="dynamic-fields" class="space-y-4">
                    <!-- Mobile Money -->
                    <div id="mobile-fields" class="hidden animate-fadeIn space-y-1.5">
                        <label class="block text-[14px] font-bold text-slate-500">Numéro de téléphone</label>
                        <input type="tel" name="phone" id="phone-input" placeholder="034 00 000 00" class="input-field font-medium">
                        <p id="phone-error" class="text-[10px] text-error-red hidden">Format invalide (ex: 034 00 000 00).</p>
                        <p class="text-[11px] text-slate-400">Un code OTP vous sera envoyé par SMS.</p>
                    </div>

                    <!-- Card -->
                    <div id="card-fields" class="hidden animate-fadeIn space-y-3">
                        <div class="space-y-1.5">
                            <label class="block text-[14px] font-bold text-slate-500">Nom sur la carte</label>
                            <input type="text" name="card_name" id="card-name" placeholder="JEAN DUPONT" class="input-field uppercase font-medium">
                            <p id="card-name-error" class="text-[10px] text-error-red hidden">Requis.</p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[14px] font-bold text-slate-500">Numéro de carte</label>
                            <div class="relative">
                                <input type="text" id="card-number" name="card_number" placeholder="0000 0000 0000 0000" class="input-field font-medium">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i id="card-logo" class="fab fa-cc-visa text-lg text-slate-300"></i>
                                </div>
                            </div>
                            <p id="card-number-error" class="text-[10px] text-error-red hidden">Invalide.</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1.5">
                                <label class="block text-[14px] font-bold text-slate-500">Expiration</label>
                                <input type="text" id="card-expiry" name="expiry" placeholder="MM/AA" class="input-field font-medium">
                                <p id="card-expiry-error" class="text-[10px] text-error-red hidden">Invalide.</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[14px] font-bold text-slate-500">CVC</label>
                                <input type="text" id="card-cvc" name="cvc" placeholder="123" class="input-field font-medium">
                                <p id="card-cvc-error" class="text-[10px] text-error-red hidden">Invalide.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Virement -->
                    <div id="transfer-fields" class="hidden animate-fadeIn space-y-3">
                        <div class="space-y-1.5">
                            <label class="block text-[14px] font-bold text-slate-500">Référence du virement</label>
                            <input type="text" name="reference" id="reference-input" placeholder="REF-123456" class="input-field font-medium">
                            <p id="reference-error" class="text-[10px] text-error-red hidden">La référence est requise.</p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[14px] font-bold text-slate-500">Preuve de virement (Capture/PDF)</label>
                            <input type="file" name="proof" id="proof-input" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-pay-blue/10 file:text-pay-blue hover:file:bg-pay-blue/20">
                            <p id="proof-error" class="text-[10px] text-error-red hidden">Veuillez uploader un justificatif.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" id="submit-btn" class="w-full h-[44px] bg-pay-blue text-white font-bold rounded-lg shadow-sm hover:bg-pay-blue-hover active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <span id="btn-text">Payer maintenant</span>
                    <div id="btn-loader" class="loader"></div>
                </button>
            </form>

            <!-- Footer -->
            <div class="pt-4 border-t border-slate-100 flex flex-col items-center gap-3">
                <div class="flex items-center gap-4 opacity-40 grayscale">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-4">
                </div>
                <p class="text-[10px] text-slate-400 font-medium">Paiement 100% sécurisé &bull; Reçu instantané</p>
            </div>
        </div>
    </div>

    <script>
        const amounts = {
            inscription: 50000,
            reinscription: 40000,
            examen: 25000,
            scolarite: 185000
        };

        function updateAmount(type) {
            const amount = amounts[type];
            document.getElementById('amount-input').value = amount;
            document.getElementById('display-amount').innerText = amount.toLocaleString('fr-FR') + '.00 Ar';
        }

        function selectMethod(method) {
            document.querySelectorAll('.method-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById('btn-' + method).classList.add('active');
            document.getElementById('provider-input').value = method;
            document.getElementById('provider-error').classList.add('hidden');

            const mobileFields = document.getElementById('mobile-fields');
            const cardFields = document.getElementById('card-fields');
            const transferFields = document.getElementById('transfer-fields');
            const phoneInput = document.getElementById('phone-input');

            // Hide all
            mobileFields.classList.add('hidden');
            cardFields.classList.add('hidden');
            transferFields.classList.add('hidden');

            if (['mvola', 'orange', 'airtel'].includes(method)) {
                mobileFields.classList.remove('hidden');
                phoneInput.required = true;
                if (method === 'mvola') phoneInput.placeholder = '034 00 000 00';
                if (method === 'orange') phoneInput.placeholder = '032 00 000 00';
                if (method === 'airtel') phoneInput.placeholder = '033 00 000 00';
            } else if (method === 'card') {
                cardFields.classList.remove('hidden');
                phoneInput.required = false;
            } else if (method === 'transfer') {
                transferFields.classList.remove('hidden');
                phoneInput.required = false;
            }
        }

        IMask(document.getElementById('phone-input'), { mask: '000 00 000 00' });
        IMask(document.getElementById('card-number'), { mask: '0000 0000 0000 0000' });
        IMask(document.getElementById('card-expiry'), { mask: '00/00' });
        IMask(document.getElementById('card-cvc'), { mask: '000' });

        document.getElementById('card-number').addEventListener('input', function(e) {
            const val = e.target.value.replace(/\s/g, '');
            const logo = document.getElementById('card-logo');
            if (val.startsWith('4')) logo.className = 'fab fa-cc-visa text-lg text-blue-600';
            else if (val.startsWith('5')) logo.className = 'fab fa-cc-mastercard text-lg text-orange-500';
            else logo.className = 'fab fa-cc-visa text-lg text-slate-300';
        });

        document.getElementById('payment-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const provider = document.getElementById('provider-input').value;
            let isValid = true;

            document.querySelectorAll('.text-error-red').forEach(el => el.classList.add('hidden'));

            if (!provider) { document.getElementById('provider-error').classList.remove('hidden'); isValid = false; }
            if (['mvola', 'orange', 'airtel'].includes(provider)) {
                const phone = document.getElementById('phone-input').value.replace(/\s/g, '');
                const phoneRegex = /^(261|0)[3-4]\d{8}$/;
                if (!phoneRegex.test(phone)) {
                    document.getElementById('phone-error').classList.remove('hidden');
                    isValid = false;
                }
            } else if (provider === 'card') {
                if (!document.getElementById('card-name').value) { document.getElementById('card-name-error').classList.remove('hidden'); isValid = false; }
                if (document.getElementById('card-number').value.length < 19) { document.getElementById('card-number-error').classList.remove('hidden'); isValid = false; }
                if (document.getElementById('card-expiry').value.length < 5) { document.getElementById('card-expiry-error').classList.remove('hidden'); isValid = false; }
                if (document.getElementById('card-cvc').value.length < 3) { document.getElementById('card-cvc-error').classList.remove('hidden'); isValid = false; }
            } else if (provider === 'transfer') {
                if (!document.getElementById('reference-input').value) { document.getElementById('reference-error').classList.remove('hidden'); isValid = false; }
                if (!document.getElementById('proof-input').value) { document.getElementById('proof-error').classList.remove('hidden'); isValid = false; }
            }

            if (isValid) {
                const btn = document.getElementById('submit-btn');
                document.getElementById('btn-text').innerText = 'Traitement...';
                document.getElementById('btn-loader').style.display = 'block';
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                this.submit();
            }
        });
    </script>
</body>

</html>