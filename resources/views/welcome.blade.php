<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduPass-MG | Plateforme Éducative Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Montserrat (Headings) & Roboto (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
                        'gradient-x': 'gradient-x 15s ease infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                    },
                    keyframes: {
                        'gradient-x': {
                            '0%, 100%': { 'background-size': '200% 200%', 'background-position': 'left center' },
                            '50%': { 'background-size': '200% 200%', 'background-position': 'right center' },
                        },
                        'float': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
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

        .glass-card {
            background: rgba(10, 11, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .text-gradient {
            background: linear-gradient(to right, #00AEEF, #7B68EE);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bg-premium-gradient {
            background: radial-gradient(circle at top right, rgba(0, 174, 239, 0.15), transparent), radial-gradient(circle at bottom left, rgba(123, 104, 238, 0.15), transparent);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0A0B3B;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #00AEEF;
        }
    </style>
</head>

<body
    class="bg-premium-dark text-slate-200 font-sans antialiased overflow-x-hidden selection:bg-premium-cyan selection:text-white">

    <!-- Navigation -->
    <nav x-data="{ open: false }"
        class="fixed top-0 w-full z-50 glass border-b border-white/5 transition-all duration-300"
        :class="{ 'bg-premium-dark/90': open }">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <div class="relative w-12 h-12 flex items-center justify-center">
                    <!-- Logo Icon -->
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-premium-cyan to-premium-purple rounded-xl opacity-20 group-hover:opacity-40 transition-opacity">
                    </div>
                    <i class="fas fa-graduation-cap text-2xl text-white relative z-10"></i>
                    <!-- Security Badge -->
                    <div
                        class="absolute -bottom-1 -right-1 bg-premium-green text-[8px] font-bold text-white px-1.5 py-0.5 rounded-full border border-premium-dark flex items-center gap-1">
                        <i class="fas fa-shield-alt"></i> 100%
                    </div>
                </div>
                <div>
                    <span
                        class="text-2xl font-heading font-black tracking-tight text-white block leading-none">EduPass<span
                            class="text-premium-cyan">-MG</span></span>
                    <span class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">L'excellence
                        académique</span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features"
                    class="text-sm font-bold text-slate-400 hover:text-white transition-colors uppercase tracking-wide">Fonctionnalités</a>
                <a href="#testimonials"
                    class="text-sm font-bold text-slate-400 hover:text-white transition-colors uppercase tracking-wide">Témoignages</a>
                <a href="#stats"
                    class="text-sm font-bold text-slate-400 hover:text-white transition-colors uppercase tracking-wide">Chiffres</a>
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-6 py-2.5 bg-premium-cyan/10 hover:bg-premium-cyan/20 text-premium-cyan border border-premium-cyan/50 rounded-full text-sm font-bold transition-all hover:shadow-[0_0_15px_rgba(0,174,239,0.3)]">
                        Tableau de bord
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-bold text-white hover:text-premium-cyan transition-colors uppercase tracking-wide">Se
                        connecter</a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="md:hidden">
                <button @click="open = !open" class="text-white p-2 focus:outline-none">
                    <i :class="open ? 'fas fa-times' : 'fas fa-bars'" class="text-2xl transition-transform duration-300"
                        :class="{ 'rotate-90': open }"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="md:hidden glass border-t border-white/5 p-6 space-y-4 absolute w-full bg-premium-dark/95 backdrop-blur-xl">
            <a href="#features" @click="open = false"
                class="block text-lg font-heading font-bold text-slate-300 hover:text-white py-2 border-b border-white/5">Fonctionnalités</a>
            <a href="#testimonials" @click="open = false"
                class="block text-lg font-heading font-bold text-slate-300 hover:text-white py-2 border-b border-white/5">Témoignages</a>
            <a href="#stats" @click="open = false"
                class="block text-lg font-heading font-bold text-slate-300 hover:text-white py-2 border-b border-white/5">Chiffres</a>
            <div class="pt-4 flex flex-col gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="w-full py-4 bg-premium-cyan text-white rounded-xl text-center font-black shadow-lg shadow-premium-cyan/20">Tableau
                        de bord</a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full py-4 text-rose-400 font-bold text-center border border-rose-400/20 rounded-xl hover:bg-rose-400/10">Se
                            déconnecter</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full py-4 bg-white/10 text-white rounded-xl text-center font-bold border border-white/10">Se
                        connecter</a>
                    <a href="#"
                        class="w-full py-4 bg-premium-cyan text-white rounded-xl text-center font-black shadow-lg shadow-premium-cyan/20">S'inscrire</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <!-- Background Image with Premium Overlay -->
        <div class="absolute inset-0 z-0">
            <!-- Using the uploaded image as hero background -->
            <img src="/images/auth-bg.jpg" alt="Étudiants Malgaches"
                class="w-full h-full object-cover opacity-30 scale-105 animate-pulse-slow">
            <div class="absolute inset-0 bg-gradient-to-b from-premium-dark/90 via-premium-dark/70 to-premium-dark">
            </div>
            <div class="absolute inset-0 bg-premium-gradient"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-8 animate-fade-in-up">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass border-premium-cyan/30 text-premium-cyan text-xs font-bold tracking-widest uppercase">
                    <span class="w-2 h-2 bg-premium-green rounded-full animate-pulse"></span>
                    Disponible à Madagascar
                </div>

                <h1 class="text-5xl md:text-7xl font-heading font-black text-white leading-[1.1] tracking-tight">
                    Votre avenir <br>
                    <span class="text-gradient">commence ici</span>
                </h1>

                <p class="text-lg md:text-xl text-slate-300 max-w-xl leading-relaxed font-light">
                    La première plateforme éducative 100% digitale à Madagascar. Payez vos frais via
                    <span class="text-white font-bold">MVola, Orange, Airtel</span>, recevez vos convocations QR et
                    suivez vos cours en ligne.
                </p>

                <div class="flex flex-wrap gap-4 pt-4">
                    <a href="{{ route('login') }}"
                        class="px-8 py-4 bg-gradient-to-r from-premium-cyan to-blue-600 text-white rounded-xl font-black text-lg shadow-2xl shadow-premium-cyan/30 hover:shadow-premium-cyan/50 hover:-translate-y-1 transition-all flex items-center gap-3">
                        Commencer maintenant
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                    <a href="#features"
                        class="px-8 py-4 glass text-white rounded-xl font-bold text-lg hover:bg-white/10 transition-all border border-white/10">
                        En savoir plus
                    </a>
                </div>

                <div class="flex items-center gap-6 pt-8 border-t border-white/5">
                    <div class="flex -space-x-4">
                        @foreach([1, 2, 3, 4] as $i)
                            <div
                                class="w-12 h-12 rounded-full border-2 border-premium-dark bg-slate-800 flex items-center justify-center overflow-hidden">
                                <img src="https://i.pravatar.cc/150?u={{ $i + 10 }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                        <div
                            class="w-12 h-12 rounded-full border-2 border-premium-dark bg-premium-surface flex items-center justify-center text-xs font-bold text-white">
                            +1k
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-1 text-yellow-400 text-sm">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-sm text-slate-400 font-medium">Approuvé par <span
                                class="text-white font-bold">1,000+ étudiants</span></p>
                    </div>
                </div>
            </div>

            <!-- Floating Elements / Visual -->
            <div class="hidden lg:block relative">
                <div class="relative z-10 animate-float">
                    <div class="glass-card p-8 rounded-[2rem] shadow-2xl border border-white/10">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-premium-cyan/20 flex items-center justify-center text-premium-cyan">
                                    <i class="fas fa-chart-pie text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white font-heading">Mon Activité</h4>
                                    <p class="text-xs text-slate-400">Semestre 1 - 2025</p>
                                </div>
                            </div>
                            <div class="px-3 py-1 rounded-lg bg-premium-green/20 text-premium-green text-xs font-bold">
                                +12% vs S1
                            </div>
                        </div>

                        <!-- Progress Bars -->
                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-slate-300">Progression Cours</span>
                                    <span class="text-white font-bold">85%</span>
                                </div>
                                <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                    <div
                                        class="h-full bg-gradient-to-r from-premium-cyan to-blue-500 w-[85%] rounded-full">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-slate-300">Paiements Validés</span>
                                    <span class="text-white font-bold">100%</span>
                                </div>
                                <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                    <div
                                        class="h-full bg-gradient-to-r from-premium-green to-emerald-400 w-full rounded-full">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Grid -->
                        <div class="grid grid-cols-2 gap-4 mt-8">
                            <div
                                class="bg-white/5 p-4 rounded-xl border border-white/5 flex flex-col items-center text-center gap-2 hover:bg-white/10 transition-colors cursor-pointer">
                                <div
                                    class="w-10 h-10 rounded-full bg-orange-500/20 text-orange-500 flex items-center justify-center">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-300">Payer (MVola)</span>
                            </div>
                            <div
                                class="bg-white/5 p-4 rounded-xl border border-white/5 flex flex-col items-center text-center gap-2 hover:bg-white/10 transition-colors cursor-pointer">
                                <div
                                    class="w-10 h-10 rounded-full bg-purple-500/20 text-purple-500 flex items-center justify-center">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-300">Mon QR</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Decorative Glows -->
                <div
                    class="absolute -top-20 -right-20 w-80 h-80 bg-premium-cyan/20 rounded-full blur-[100px] animate-pulse-slow">
                </div>
                <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-premium-purple/20 rounded-full blur-[100px] animate-pulse-slow"
                    style="animation-delay: 2s;"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 relative bg-premium-surface/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <h2 class="text-premium-cyan font-black tracking-widest uppercase text-xs">Pourquoi choisir EduPass ?
                </h2>
                <h3 class="text-3xl md:text-5xl font-heading font-black text-white tracking-tight">
                    Une expérience <span class="text-gradient">sans compromis</span>
                </h3>
                <p class="text-slate-400 text-lg">
                    Conçu pour les étudiants malgaches, optimisé pour le bas débit et sécurisé par les dernières
                    technologies.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div
                    class="group glass p-8 rounded-[2rem] border-white/5 hover:border-premium-cyan/30 hover:bg-premium-surface transition-all hover:-translate-y-2 duration-300">
                    <div
                        class="w-16 h-16 rounded-2xl bg-orange-500/10 flex items-center justify-center text-orange-500 mb-6 group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all shadow-lg shadow-orange-500/5">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-3 font-heading">Paiement Mobile</h4>
                    <p class="text-slate-400 leading-relaxed text-sm">
                        Plus de files d'attente. Payez vos écolages instantanément via <span
                            class="text-white font-bold">MVola, Orange Money ou Airtel Money</span>. Reçu numérique
                        immédiat.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="group glass p-8 rounded-[2rem] border-white/5 hover:border-premium-cyan/30 hover:bg-premium-surface transition-all hover:-translate-y-2 duration-300">
                    <div
                        class="w-16 h-16 rounded-2xl bg-premium-cyan/10 flex items-center justify-center text-premium-cyan mb-6 group-hover:scale-110 group-hover:bg-premium-cyan group-hover:text-white transition-all shadow-lg shadow-premium-cyan/5">
                        <i class="fas fa-qrcode text-2xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-3 font-heading">Convocations QR</h4>
                    <p class="text-slate-400 leading-relaxed text-sm">
                        Sécurité maximale. Vos convocations d'examen sont générées avec un <span
                            class="text-white font-bold">QR Code unique</span> infalsifiable, vérifiable hors-ligne.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div
                    class="group glass p-8 rounded-[2rem] border-white/5 hover:border-premium-cyan/30 hover:bg-premium-surface transition-all hover:-translate-y-2 duration-300">
                    <div
                        class="w-16 h-16 rounded-2xl bg-premium-purple/10 flex items-center justify-center text-premium-purple mb-6 group-hover:scale-110 group-hover:bg-premium-purple group-hover:text-white transition-all shadow-lg shadow-premium-purple/5">
                        <i class="fas fa-laptop-code text-2xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-3 font-heading">Suivi Académique</h4>
                    <p class="text-slate-400 leading-relaxed text-sm">
                        Tout en un seul endroit. Consultez vos notes, téléchargez vos bulletins et suivez votre
                        progression en temps réel, même sur mobile.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section (NEW) -->
    <section id="testimonials" class="py-24 relative overflow-hidden">
        <!-- Background Decoration -->
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-premium-gradient opacity-20 blur-[120px] pointer-events-none">
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div class="max-w-2xl">
                    <h2 class="text-premium-cyan font-black tracking-widest uppercase text-xs mb-4">Témoignages</h2>
                    <h3 class="text-3xl md:text-5xl font-heading font-black text-white tracking-tight">
                        Ils ont transformé leur <br> vie étudiante
                    </h3>
                </div>
                <div class="flex gap-2">
                    <button
                        class="w-12 h-12 rounded-full border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition-colors"><i
                            class="fas fa-arrow-left"></i></button>
                    <button
                        class="w-12 h-12 rounded-full bg-premium-cyan text-white flex items-center justify-center hover:bg-premium-cyan/80 transition-colors shadow-lg shadow-premium-cyan/20"><i
                            class="fas fa-arrow-right"></i></button>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Testimonial 1 -->
                <div class="glass p-8 rounded-3xl border-white/5 relative">
                    <i class="fas fa-quote-right absolute top-8 right-8 text-4xl text-white/5"></i>
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://i.pravatar.cc/150?u=33"
                            class="w-12 h-12 rounded-full border-2 border-premium-cyan">
                        <div>
                            <h5 class="font-bold text-white">Rindra R.</h5>
                            <p class="text-xs text-slate-400">Étudiant en Gestion</p>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        "Avant, je faisais la queue pendant des heures pour payer mes frais. Avec EduPass, je paye via
                        MVola en 2 minutes depuis chez moi. C'est une révolution !"
                    </p>
                    <div class="flex text-yellow-500 text-xs gap-1 mt-4">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="glass p-8 rounded-3xl border-white/5 relative bg-white/[0.02]">
                    <i class="fas fa-quote-right absolute top-8 right-8 text-4xl text-white/5"></i>
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://i.pravatar.cc/150?u=47"
                            class="w-12 h-12 rounded-full border-2 border-premium-purple">
                        <div>
                            <h5 class="font-bold text-white">Aina S.</h5>
                            <p class="text-xs text-slate-400">Étudiante en Droit</p>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        "J'adore l'interface ! C'est super moderne et facile à utiliser sur mon téléphone. Avoir mes
                        convocations en QR code me rassure énormément."
                    </p>
                    <div class="flex text-yellow-500 text-xs gap-1 mt-4">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="glass p-8 rounded-3xl border-white/5 relative">
                    <i class="fas fa-quote-right absolute top-8 right-8 text-4xl text-white/5"></i>
                    <div class="flex items-center gap-4 mb-6">
                        <img src="https://i.pravatar.cc/150?u=12"
                            class="w-12 h-12 rounded-full border-2 border-premium-green">
                        <div>
                            <h5 class="font-bold text-white">Tahina M.</h5>
                            <p class="text-xs text-slate-400">Admin Scolarité</p>
                        </div>
                    </div>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        "La gestion des étudiants est devenue tellement plus simple. La vérification des paiements est
                        automatique, ce qui nous fait gagner un temps précieux."
                    </p>
                    <div class="flex text-yellow-500 text-xs gap-1 mt-4">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                            class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="glass rounded-[3rem] p-12 md:p-20 border-white/5 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-premium-dark/50 to-transparent"></div>
                <div class="relative z-10 grid md:grid-cols-4 gap-12 text-center">
                    <div class="space-y-2">
                        <p class="text-5xl font-heading font-black text-white">1k+</p>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Étudiants Actifs</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-5xl font-heading font-black text-premium-cyan">98%</p>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Taux de Réussite</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-5xl font-heading font-black text-white">24/7</p>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Support Technique</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-5xl font-heading font-black text-premium-green">100%</p>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Sécurisé</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-16 border-t border-white/5 bg-premium-dark relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-premium-cyan to-premium-purple rounded-xl flex items-center justify-center text-white shadow-lg shadow-premium-cyan/20">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <span class="text-2xl font-heading font-black text-white">EduPass<span
                                class="text-premium-cyan">-MG</span></span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                        La plateforme de référence pour la gestion académique à Madagascar. Simplifiez votre vie
                        étudiante dès aujourd'hui.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Liens Rapides</h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-premium-cyan transition-colors">À propos</a></li>
                        <li><a href="#features" class="hover:text-premium-cyan transition-colors">Fonctionnalités</a>
                        </li>
                        <li><a href="#" class="hover:text-premium-cyan transition-colors">Tarifs</a></li>
                        <li><a href="#" class="hover:text-premium-cyan transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Légal</h4>
                    <ul class="space-y-4 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-premium-cyan transition-colors">Confidentialité</a></li>
                        <li><a href="#" class="hover:text-premium-cyan transition-colors">Conditions d'utilisation</a>
                        </li>
                        <li><a href="#" class="hover:text-premium-cyan transition-colors">Mentions légales</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-500 text-sm font-medium">© 2025 EduPass-MG. Fièrement développé à Madagascar.</p>
                <div class="flex gap-6">
                    @foreach(['facebook', 'twitter', 'linkedin', 'instagram'] as $social)
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-premium-cyan hover:text-white transition-all">
                            <i class="fab fa-{{ $social }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Floating CTA -->
    <div class="fixed bottom-6 left-6 right-6 z-40 md:hidden">
        <a href="{{ route('login') }}"
            class="w-full py-4 bg-premium-cyan text-white rounded-2xl font-black text-lg shadow-2xl shadow-premium-cyan/40 flex items-center justify-center gap-3 animate-bounce-slow">
            <i class="fas fa-bolt"></i>
            Accéder à mon espace
        </a>
    </div>

</body>

</html>