<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EduPass-MG') }} - Inscription</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Roboto', 'sans-serif'],
                        heading: ['Montserrat', 'sans-serif']
                    },
                    colors: {
                        premium: {
                            dark: '#0A0B3B',       // Deep Blue (Base)
                            cyan: '#00AEEF',       // Cyan (Secondary/Titles)
                            green: '#228B22',      // Malagasy Green (Success/Accent)
                            purple: '#7B68EE',     // Soft Purple (Gradients)
                            surface: '#111240'     // Slightly lighter background
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        'float': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
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

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full font-sans antialiased text-slate-200">

    <!-- Main Container with Background -->
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden p-4 sm:p-6 lg:p-8">

        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="/images/premium-bg.png" alt="Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-premium-dark/40 backdrop-blur-[2px]"></div>
        </div>


        <!-- Register Card Container -->
        <div class="relative z-10 w-full max-w-[500px] mx-auto flex items-center justify-center">

            <div class="w-full rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 bg-premium-surface/80 backdrop-blur-xl p-8 md:p-10"
                x-data="{ 
                    step: 1, 
                    role: 'student',
                    cinPreview: null,
                    photoPreview: null,
                    errorMessage: '',
                    validateStep1() {
                        const name = document.getElementById('name').value;
                        const email = document.getElementById('email').value;
                        const phone = document.getElementById('phone').value;
                        if (!name || !email || !phone) {
                            this.errorMessage = 'Veuillez remplir les champs obligatoires.';
                            return false;
                        }
                        this.errorMessage = '';
                        return true;
                    },
                    validateStep2() {
                        const cin = document.getElementById('cin').files.length > 0;
                        const photo = document.getElementById('photo').files.length > 0;
                        const matricule = document.getElementById('matricule').value;
                        
                        if (this.role === 'student' && !matricule) {
                            this.errorMessage = 'Le matricule est obligatoire.';
                            return false;
                        }
                        
                        if (!cin || !photo) {
                            this.errorMessage = 'Upload de CIN et Photo obligatoire.';
                            return false;
                        }

                        this.errorMessage = '';
                        return true;
                    },
                    nextStep() {
                        if (this.step === 1) {
                            if (this.validateStep1()) this.step++;
                        } else if (this.step === 2) {
                            if (this.validateStep2()) this.step++;
                        }
                    },
                    handleFileSelect(event, previewVar) {
                        const file = event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => { this[previewVar] = e.target.result };
                            reader.readAsDataURL(file);
                        }
                    }
                }">

                <!-- Logo & Header -->
                <div class="text-center mb-8">
                    <a href="/"
                        class="inline-flex items-center gap-2 mb-4 group transition-transform hover:scale-105">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-premium-cyan to-premium-purple rounded-xl flex items-center justify-center text-white shadow-lg shadow-premium-cyan/20">
                            <i class="fas fa-graduation-cap text-lg"></i>
                        </div>
                        <span class="text-xl font-heading font-black text-white">EduPass<span
                                class="text-premium-cyan">-MG</span></span>
                    </a>
                    <h2 class="text-2xl font-heading font-bold text-white mb-1">C'est parti !</h2>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Rejoignez-nous en 3 étapes</p>
                </div>

                <!-- Stepper Progress (Minimal) -->
                <div class="flex items-center justify-center gap-4 mb-8">
                    <template x-for="i in [1, 2, 3]">
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-black text-[10px] transition-all duration-300 border-2"
                                :class="step >= i ? 'bg-premium-cyan border-premium-cyan text-white shadow-lg shadow-premium-cyan/20' : 'bg-transparent border-white/10 text-slate-500'"
                                x-text="i">
                            </div>
                            <div x-show="i < 3" class="w-8 h-0.5 ml-4 bg-white/10 rounded-full" :class="step > i ? 'bg-premium-cyan' : ''"></div>
                        </div>
                    </template>
                </div>

                <!-- Errors -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-[11px] text-rose-400">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div x-show="errorMessage" x-transition class="mb-6 p-3 bg-amber-500/10 border border-amber-500/20 rounded-2xl flex items-center gap-3">
                    <i class="fas fa-info-circle text-amber-500 text-sm"></i>
                    <p class="text-amber-400 text-[11px] font-bold" x-text="errorMessage"></p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4" enctype="multipart/form-data">
                    @csrf

                    <!-- STEP 1: Basic Info -->
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nom complet</label>
                                <input id="name" type="text" name="name" :value="old('name')" required class="block w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-premium-cyan focus:border-transparent transition-all" placeholder="Jean Dupont" />
                            </div>

                            <div class="space-y-1.5">
                                <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Email</label>
                                <input id="email" type="email" name="email" :value="old('email')" required class="block w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-premium-cyan focus:border-transparent transition-all" placeholder="votre@email.mg" />
                            </div>

                            <div class="space-y-1.5">
                                <label for="phone" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Téléphone</label>
                                <input id="phone" type="text" name="phone" :value="old('phone')" required class="block w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-premium-cyan focus:border-transparent transition-all" placeholder="+261 34 00 000 00" />
                            </div>

                            <div class="space-y-1.5">
                                <label for="role_type" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Type de compte</label>
                                <select id="role_type" name="role_type" x-model="role" class="block w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-premium-cyan focus:border-transparent transition-all appearance-none cursor-pointer">
                                    <option value="student" class="bg-premium-dark text-white">Étudiant</option>
                                    <option value="staff" class="bg-premium-dark text-white">Personnel (Staff)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Identity -->
                    <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="space-y-4">
                            <div x-show="role === 'student'" class="space-y-1.5">
                                <label for="matricule" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Matricule Étudiant</label>
                                <input id="matricule" type="text" name="matricule" :value="old('matricule')" class="block w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-premium-cyan focus:border-transparent transition-all" placeholder="ETU-2025-001" />
                            </div>

                            <div x-show="role === 'staff'" class="space-y-1.5">
                                <label for="staff_role" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Fonction</label>
                                <select id="staff_role" name="staff_role" class="block w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-premium-cyan focus:border-transparent transition-all appearance-none cursor-pointer">
                                    <option value="comptable" class="bg-premium-dark text-white">Comptable</option>
                                    <option value="scolarite" class="bg-premium-dark text-white">Scolarité</option>
                                    <option value="controleur" class="bg-premium-dark text-white">Contrôleur</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">CIN</label>
                                    <label for="cin" class="flex flex-col items-center justify-center w-full h-28 border border-dashed border-white/10 rounded-2xl hover:border-premium-cyan/50 hover:bg-white/5 transition-all cursor-pointer overflow-hidden relative">
                                        <input type="file" id="cin" name="cin" class="hidden" @change="handleFileSelect($event, 'cinPreview')" accept="image/*,.pdf">
                                        <template x-if="!cinPreview">
                                            <div class="text-center">
                                                <i class="fas fa-id-card text-xl text-slate-500 mb-1"></i>
                                                <p class="text-[9px] text-slate-400 font-black">SCAN CIN</p>
                                            </div>
                                        </template>
                                        <template x-if="cinPreview">
                                            <img :src="cinPreview" class="w-full h-full object-cover">
                                        </template>
                                    </label>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Photo</label>
                                    <label for="photo" class="flex flex-col items-center justify-center w-full h-28 border border-dashed border-white/10 rounded-2xl hover:border-premium-cyan/50 hover:bg-white/5 transition-all cursor-pointer overflow-hidden relative">
                                        <input type="file" id="photo" name="photo" class="hidden" @change="handleFileSelect($event, 'photoPreview')" accept="image/*">
                                        <template x-if="!photoPreview">
                                            <div class="text-center">
                                                <i class="fas fa-camera text-xl text-slate-500 mb-1"></i>
                                                <p class="text-[9px] text-slate-400 font-black">IDENTITÉ</p>
                                            </div>
                                        </template>
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" class="w-full h-full object-cover">
                                        </template>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Security -->
                    <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Mot de passe</label>
                                <input id="password" type="password" name="password" required autocomplete="new-password" class="block w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-premium-cyan focus:border-transparent transition-all" placeholder="••••••••" />
                            </div>

                            <div class="space-y-1.5">
                                <label for="password_confirmation" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Confirmer</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required class="block w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-premium-cyan focus:border-transparent transition-all" placeholder="••••••••" />
                            </div>

                            <div class="flex items-start gap-3 p-3 bg-white/5 rounded-2xl border border-white/5">
                                <input id="terms" type="checkbox" required class="w-4 h-4 mt-0.5 rounded border-white/10 bg-white/5 text-premium-cyan focus:ring-premium-cyan transition-all">
                                <label for="terms" class="text-[10px] text-slate-400 font-medium leading-tight">J'accepte les conditions générales d'utilisation d'EduPass Madagascar.</label>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <div class="pt-4 flex gap-3">
                        <button type="button" x-show="step > 1" @click="step--" class="flex-1 py-2.5 bg-white/5 text-slate-300 font-bold rounded-2xl hover:bg-white/10 transition-all text-sm">Précédent</button>
                        <button type="button" x-show="step < 3" @click="nextStep()" class="flex-1 py-2.5 bg-premium-cyan text-white font-black rounded-2xl shadow-lg shadow-premium-cyan/10 hover:shadow-premium-cyan/30 hover:-translate-y-0.5 transition-all text-sm">Continuer</button>
                        <button type="submit" x-show="step === 3" class="flex-1 py-2.5 bg-gradient-to-r from-premium-cyan to-blue-600 text-white font-black rounded-2xl shadow-xl shadow-premium-cyan/10 hover:shadow-premium-cyan/30 hover:-translate-y-0.5 transition-all text-sm">Finaliser l'inscription</button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-6 border-t border-white/5">
                        <p class="text-slate-500 text-[11px] font-medium">Déjà un compte ? <a href="{{ route('login') }}" class="text-white font-bold hover:text-premium-cyan transition-colors ml-1">Se connecter</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>