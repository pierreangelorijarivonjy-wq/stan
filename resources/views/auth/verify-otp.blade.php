<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EduPass-MG') }} - Vérification OTP</title>

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
                            dark: '#0A0B3B',       // Deep Blue
                            cyan: '#00AEEF',       // Cyan
                            purple: '#7B68EE',     // Soft Purple
                            surface: '#111240'     // Background
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(17, 18, 64, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="h-full font-sans antialiased text-slate-200"
      x-data="{ 
        toasts: [],
        addToast(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            setTimeout(() => this.removeToast(id), 5000);
        },
        removeToast(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
      }"
      x-init="
        @if(session('success')) addToast('{{ session('success') }}', 'success'); @endif
        @if(session('error')) addToast('{{ session('error') }}', 'error'); @endif
        @if($errors->any()) addToast('{{ $errors->first() }}', 'error'); @endif
      ">

    <div class="min-h-screen flex items-center justify-center relative overflow-hidden p-6">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="/images/premium-bg.png" alt="Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-premium-dark/50 backdrop-blur-[2px]"></div>
        </div>

        <!-- Verification Card -->
        <div class="relative z-10 w-full max-w-[400px]">
            <div class="glass rounded-[3rem] p-10 shadow-2xl text-center">
                <!-- Icon -->
                <div class="mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-premium-cyan to-premium-purple rounded-2xl flex items-center justify-center text-white shadow-lg shadow-premium-cyan/20 mx-auto mb-4">
                        <i class="fas fa-shield-check text-xl"></i>
                    </div>
                    <h2 class="text-2xl font-heading font-black text-white">Vérification OTP</h2>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-2">Code envoyé par email</p>
                </div>

                <!-- OTP Form -->
                <form method="POST" action="{{ route('otp.verify') }}" class="space-y-6">
                    @csrf
                    <div>
                        <input type="text" name="otp" id="otp" maxlength="6" required autofocus
                               class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-4 text-center text-3xl font-black tracking-[0.4em] text-white focus:ring-2 focus:ring-premium-cyan outline-none transition-all"
                               placeholder="••••••">
                        @error('otp')
                            <p class="mt-2 text-[10px] text-rose-500 font-bold uppercase">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-premium-cyan to-blue-600 text-white font-black py-3.5 rounded-2xl shadow-lg shadow-premium-cyan/10 hover:shadow-premium-cyan/30 hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-3">
                        <span>Vérifier le code</span>
                    </button>
                </form>

                <!-- Actions -->
                <div class="mt-8 pt-6 border-t border-white/5 space-y-4">
                    <form method="POST" action="{{ route('otp.resend') }}">
                        @csrf
                        <button type="submit" class="text-[10px] font-bold text-slate-500 hover:text-white transition-colors uppercase tracking-widest flex items-center justify-center gap-2 mx-auto">
                            <i class="fas fa-redo-alt text-[8px]"></i> Renvoyer le code
                        </button>
                    </form>
                    
                    <a href="{{ route('login') }}" class="inline-block text-[10px] font-bold text-premium-cyan hover:text-white transition-colors uppercase tracking-widest">
                        <i class="fas fa-arrow-left mr-1"></i> Mode de secours
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-[9px] font-black text-slate-600 uppercase tracking-[0.2em]">
                    <i class="fas fa-lock text-premium-cyan"></i> Sécurisé par EduPass
                </p>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="fixed top-6 right-6 z-50 space-y-4 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8 scale-90"
                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-x-8 scale-90"
                 :class="toast.type === 'success' ? 'bg-premium-surface/90 border-emerald-500/20 text-emerald-400' : 'bg-premium-surface/90 border-rose-500/20 text-rose-400'"
                 class="pointer-events-auto backdrop-blur-xl border p-4 rounded-2xl shadow-2xl flex items-center gap-4 min-w-[280px]">
                <div class="flex-1 text-xs font-bold" x-text="toast.message"></div>
                <button @click="removeToast(toast.id)" class="text-slate-500 hover:text-white transition-colors"><i class="fas fa-times"></i></button>
            </div>
        </template>
    </div>
</body>
</html>