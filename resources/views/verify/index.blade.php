<!DOCTYPE html>
<html lang="fr" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification - EduPass-MG Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    animation: {
                        'scan': 'scan 3s linear infinite',
                    },
                    keyframes: {
                        'scan': {
                            '0%, 100%': { top: '0%' },
                            '50%': { top: '100%' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .bg-premium-dark {
            background: radial-gradient(circle at top right, rgba(79, 70, 229, 0.1), transparent), radial-gradient(circle at bottom left, rgba(124, 58, 237, 0.1), transparent), #0F172A;
        }
    </style>
</head>

<body class="bg-premium-dark min-h-screen flex items-center justify-center px-4 font-sans antialiased text-slate-200">
    <div class="max-w-md w-full">
        <!-- Back Link -->
        <div class="mb-8">
            <a href="/"
                class="inline-flex items-center gap-2 text-slate-500 hover:text-white transition group font-bold text-sm uppercase tracking-widest">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Retour à l'accueil
            </a>
        </div>

        <div class="glass rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
            <!-- Decorative Glow -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>

            <div class="text-center mb-12">
                <div class="relative w-20 h-20 mx-auto mb-8 group">
                    <div
                        class="absolute inset-0 bg-indigo-500/20 rounded-2xl blur-xl group-hover:bg-indigo-500/40 transition-all">
                    </div>
                    <div
                        class="relative w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl border border-white/10">
                        <i class="fas fa-qrcode text-3xl text-white"></i>
                        <!-- Animated Scan Line -->
                        <div
                            class="absolute left-0 right-0 h-0.5 bg-cyan-400 shadow-[0_0_10px_rgba(34,211,238,0.8)] animate-scan opacity-50">
                        </div>
                    </div>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight mb-2">Vérification</h1>
                <p class="text-slate-500 font-medium">Scannez ou entrez le code de vérification</p>
            </div>

            <form id="verifyForm" class="space-y-8">
                @csrf
                <div class="space-y-2">
                    <label for="code" class="block text-xs font-black text-slate-500 uppercase tracking-widest">Code de
                        vérification</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i
                                class="fas fa-terminal text-slate-600 text-sm group-focus-within:text-indigo-400 transition-colors"></i>
                        </div>
                        <input type="text" id="code" name="code" placeholder="Entrez le code manuellement"
                            class="w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-mono text-lg"
                            required>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-white text-indigo-600 py-4 rounded-2xl font-black hover:scale-105 transition-all shadow-xl shadow-white/5 flex items-center justify-center gap-3 group">
                    <span>Vérifier maintenant</span>
                    <i class="fas fa-shield-check text-sm group-hover:rotate-12 transition-transform"></i>
                </button>
            </form>

            <div id="result" class="mt-10 hidden animate-in fade-in slide-in-from-top-4 duration-500"></div>

            <!-- Footer -->
            <div class="mt-12 pt-8 border-t border-white/5 text-center">
                <p class="text-[10px] text-slate-600 font-black uppercase tracking-[0.2em]">© 2025 EduPass-MG – Sécurisé
                    par Blockchain</p>
            </div>
        </div>
    </div>

    <script>
        const verifyForm = document.getElementById('verifyForm');
        const codeInput = document.getElementById('code');
        const resultDiv = document.getElementById('result');

        const performVerification = async (code, sig = null) => {
            const submitBtn = verifyForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const response = await fetch('/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code, sig })
                });

                const data = await response.json();

                if (data.valid) {
                    let content = '';
                    const badgeColor = data.data.is_certified ? 'emerald' : 'blue';
                    const badgeText = data.data.is_certified ? 'Certifié & Signé' : 'Valide';

                    if (data.type === 'convocation') {
                        content = `
                        <div class="bg-${badgeColor}-500/10 border border-${badgeColor}-500/20 rounded-[2rem] p-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-${badgeColor}-500/20 text-${badgeColor}-400 flex items-center justify-center shadow-lg shadow-${badgeColor}-500/5">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-white">Convocation ${badgeText}</h3>
                                    <p class="text-${badgeColor}-400/70 text-xs font-bold uppercase tracking-widest">Authentifié</p>
                                </div>
                            </div>
                            
                            ${data.data.photo ? `
                            <div class="mb-6 flex justify-center">
                                <img src="/storage/${data.data.photo}" class="w-24 h-24 rounded-2xl border-2 border-white/10 object-cover shadow-xl" alt="Photo étudiant">
                            </div>
                            ` : ''}

                            <div class="space-y-4">
                                ${Object.entries({
                            'Étudiant': data.data.student_name,
                            'Matricule': data.data.matricule,
                            'Session': data.data.session_type,
                            'Date & Heure': `${data.data.session_date} à ${data.data.session_time}`,
                            'Centre': data.data.center,
                            'Salle': data.data.room || 'N/A'
                        }).map(([label, value]) => `
                                    <div class="flex justify-between items-center border-b border-white/5 pb-3">
                                        <span class="text-slate-500 text-[10px] font-black uppercase tracking-widest">${label}</span>
                                        <span class="text-white font-black text-sm">${value}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        `;
                    } else if (data.type === 'payment') {
                        content = `
                        <div class="bg-${badgeColor}-500/10 border border-${badgeColor}-500/20 rounded-[2rem] p-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-${badgeColor}-500/20 text-${badgeColor}-400 flex items-center justify-center shadow-lg shadow-${badgeColor}-500/5">
                                    <i class="fas fa-check-circle text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-white">Paiement ${badgeText}</h3>
                                    <p class="text-${badgeColor}-400/70 text-xs font-bold uppercase tracking-widest">Confirmé</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                ${Object.entries({
                            'Étudiant': data.data.student_name,
                            'Montant': data.data.amount,
                            'Type': data.data.type,
                            'Statut': `<span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase ${data.data.status === 'paid' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400'}">${data.data.status}</span>`,
                            'Fournisseur': data.data.provider
                        }).map(([label, value]) => `
                                    <div class="flex justify-between items-center border-b border-white/5 pb-3">
                                        <span class="text-slate-500 text-[10px] font-black uppercase tracking-widest">${label}</span>
                                        <span class="text-white font-black text-sm">${value}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        `;
                    } else if (data.type === 'transcript') {
                        content = `
                        <div class="bg-${badgeColor}-500/10 border border-${badgeColor}-500/20 rounded-[2rem] p-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-${badgeColor}-500/20 text-${badgeColor}-400 flex items-center justify-center shadow-lg shadow-${badgeColor}-500/5">
                                    <i class="fas fa-graduation-cap text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-white">Relevé ${badgeText}</h3>
                                    <p class="text-${badgeColor}-400/70 text-xs font-bold uppercase tracking-widest">Académique</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                ${Object.entries({
                            'Étudiant': data.data.student_name,
                            'Matricule': data.data.matricule,
                            'Session': data.data.session_type,
                            'Statut': data.data.status
                        }).map(([label, value]) => `
                                    <div class="flex justify-between items-center border-b border-white/5 pb-3">
                                        <span class="text-slate-500 text-[10px] font-black uppercase tracking-widest">${label}</span>
                                        <span class="text-white font-black text-sm">${value}</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        `;
                    }
                    resultDiv.innerHTML = content;
                } else {
                    resultDiv.innerHTML = `
                    <div class="bg-rose-500/10 border border-rose-500/20 rounded-[2rem] p-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center shadow-lg shadow-rose-500/5">
                                <i class="fas fa-times-circle text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-white">Code Invalide</h3>
                                <p class="text-rose-400/70 text-xs font-bold uppercase tracking-widest">${data.message}</p>
                            </div>
                        </div>
                    </div>
                    `;
                }
                resultDiv.classList.remove('hidden');
            } catch (error) {
                resultDiv.innerHTML = `
                <div class="bg-rose-500/10 border border-rose-500/20 rounded-[2rem] p-8 text-center">
                    <p class="text-rose-400 font-black uppercase tracking-widest text-xs">Erreur de vérification système</p>
                </div>
                `;
                resultDiv.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Vérifier maintenant</span><i class="fas fa-shield-check text-sm group-hover:rotate-12 transition-transform"></i>';
            }
        };

        verifyForm.addEventListener('submit', (e) => {
            e.preventDefault();
            performVerification(codeInput.value);
        });

        // Auto-verify if params in URL
        window.addEventListener('load', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const code = urlParams.get('code');
            const sig = urlParams.get('sig');

            if (code) {
                codeInput.value = code;
                performVerification(code, sig);
            }
        });
    </script>
</body>

</html>