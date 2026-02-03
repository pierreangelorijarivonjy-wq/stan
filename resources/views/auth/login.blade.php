<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EduPass-MG') }} - Connexion</title>

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
    </style>
</head>

<body class="h-full font-sans antialiased text-slate-200">

    <!-- Main Container with Background -->
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden">

        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="/images/premium-bg.png" alt="Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-premium-dark/40 backdrop-blur-[2px]"></div>
        </div>


        <!-- Login Card Container -->
        <div class="relative z-10 w-full max-w-[450px] mx-auto px-4 sm:px-6 flex items-center justify-center">

            <div
                class="w-full rounded-[2.5rem] overflow-hidden shadow-2xl border border-white/10 bg-premium-surface/80 backdrop-blur-xl p-8 md:p-10">

                <!-- Logo & Header -->
                <div class="text-center mb-8">
                    <a href="/"
                        class="inline-flex items-center gap-2 mb-6 group transition-transform hover:scale-105">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-premium-cyan to-premium-purple rounded-xl flex items-center justify-center text-white shadow-lg shadow-premium-cyan/20">
                            <i class="fas fa-graduation-cap text-lg"></i>
                        </div>
                        <span class="text-xl font-heading font-black text-white">EduPass<span
                                class="text-premium-cyan">-MG</span></span>
                    </a>
                    <h2 class="text-2xl font-heading font-bold text-white mb-1">Bon retour !</h2>
                    <p class="text-slate-400 text-xs font-medium uppercase tracking-wider">Connexion sécurisée</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-1.5">
                        <label for="email"
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Identifiant</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-envelope text-slate-500 group-focus-within:text-premium-cyan transition-colors text-sm"></i>
                            </div>
                            <input id="email"
                                class="block w-full pl-11 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-white text-sm placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-premium-cyan focus:border-transparent transition-all font-medium"
                                type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username" placeholder="votre@email.mg" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <div class="flex justify-between items-center ml-1">
                            <label for="password"
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Mot de
                                passe</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-[10px] font-bold text-premium-cyan hover:text-premium-cyan/80 transition-colors">
                                    Oublié ?
                                </a>
                            @endif
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-lock text-slate-500 group-focus-within:text-premium-cyan transition-colors text-sm"></i>
                            </div>
                            <input id="password"
                                class="block w-full pl-11 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-white text-sm placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-premium-cyan focus:border-transparent transition-all font-medium"
                                type="password" name="password" required autocomplete="current-password"
                                placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & 2FA Info (Compact) -->
                    <div class="flex items-center justify-between px-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox"
                                class="w-3.5 h-3.5 rounded border-white/10 bg-white/5 text-premium-cyan shadow-sm focus:ring-premium-cyan focus:ring-offset-premium-dark"
                                name="remember">
                            <span
                                class="ml-2 text-xs text-slate-400 group-hover:text-slate-300 transition-colors">Se souvenir</span>
                        </label>

                        <div class="flex items-center gap-1.5 text-[9px] text-premium-green font-bold bg-premium-green/5 px-2 py-1 rounded-full border border-premium-green/10">
                            <i class="fas fa-shield-alt"></i> 2FA
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3 bg-gradient-to-r from-premium-cyan to-blue-600 text-white text-sm font-black rounded-2xl shadow-lg shadow-premium-cyan/10 hover:shadow-premium-cyan/30 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-3 group">
                        <span>Accéder au portail</span>
                        <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>

                <!-- Footer Links -->
                <div class="mt-8 text-center pt-6 border-t border-white/5">
                    <p class="text-slate-500 text-[11px] mb-4">
                        Pas encore inscrit ?
                        <a href="{{ route('register') }}"
                            class="text-white font-bold hover:text-premium-cyan transition-colors">Créer un compte</a>
                    </p>
                    <div class="flex justify-center gap-4 text-[9px] text-slate-600 font-bold uppercase tracking-widest">
                        <a href="#" class="hover:text-slate-400 transition-colors">Aide</a>
                        <span>&bull;</span>
                        <a href="#" class="hover:text-slate-400 transition-colors">RGPD</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>