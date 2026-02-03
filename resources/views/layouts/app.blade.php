<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EduPass-MG') }} - Plateforme Éducative Premium</title>

    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        premium: {
                            dark: '#0A0B3B',       // Deep Blue
                            sidebar: '#1E3A8A',    // Lighter Blue
                            cyan: '#00AEEF',       // Cyan
                            green: '#228B22',      // Malagasy Green
                            purple: '#7B68EE',     // Soft Purple
                        }
                    },
                    animation: {
                        'gradient-x': 'gradient-x 15s ease infinite',
                    },
                    keyframes: {
                        'gradient-x': {
                            '0%, 100%': { 'background-size': '200% 200%', 'background-position': 'left center' },
                            '50%': { 'background-size': '200% 200%', 'background-position': 'right center' },
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

        .text-gradient {
            background: linear-gradient(to right, #818CF8, #C084FC, #22D3EE);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-premium-dark text-slate-200 overflow-x-hidden"
    x-data="{ sidebarOpen: true, logoutModalOpen: false }">
    <div class="min-h-screen flex transition-all duration-300">

        <!-- Sidebar Desktop -->
        <aside :class="sidebarOpen ? 'w-72' : 'w-20'"
            class="hidden lg:flex flex-col bg-premium-sidebar/50 backdrop-blur-xl border-r border-white/5 sticky top-0 h-screen z-50 transition-all duration-300">

            <!-- Logo Section -->
            <div class="p-6 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group overflow-hidden">
                    <div
                        class="w-10 h-10 shrink-0 bg-gradient-to-br from-premium-cyan to-premium-purple rounded-xl flex items-center justify-center text-white shadow-lg shadow-premium-cyan/20">
                        <i class="fas fa-graduation-cap text-lg"></i>
                    </div>
                    <div :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
                        class="transition-all duration-300 whitespace-nowrap overflow-hidden">
                        <span class="font-heading font-black text-xl tracking-tight text-white block">EduPass<span
                                class="text-premium-cyan">-MG</span></span>
                        <span class="text-[10px] text-slate-400 font-medium block">Excellence Académique</span>
                    </div>
                </a>
                <!-- Collapse Button -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-slate-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/5">
                    <i :class="sidebarOpen ? 'fa-chevron-left' : 'fa-chevron-right'" class="fas text-xs"></i>
                </button>
            </div>

            <nav class="flex-1 px-4 space-y-6 overflow-y-auto py-4">
                <!-- Main Menu -->
                <div>
                    <p :class="sidebarOpen ? 'opacity-100' : 'opacity-0'"
                        class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 transition-opacity duration-300 whitespace-nowrap">
                        Menu Principal</p>
                    <div class="space-y-2">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('dashboard') ? 'bg-premium-cyan text-white shadow-lg shadow-premium-cyan/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                            title="Tableau de bord">
                            <i class="fas fa-th-large text-lg w-6 text-center"></i>
                            <span :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
                                class="font-bold transition-all duration-300 whitespace-nowrap overflow-hidden">Tableau
                                de bord</span>
                        </a>
                        <a href="{{ route('home') }}"
                            class="flex items-center gap-4 px-4 py-3 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-all group"
                            title="Accueil Site">
                            <i class="fas fa-home text-lg w-6 text-center"></i>
                            <span :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
                                class="font-bold transition-all duration-300 whitespace-nowrap overflow-hidden">Accueil
                                Site</span>
                        </a>
                        <a href="{{ route('courses') }}"
                            class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('courses*') ? 'bg-premium-purple text-white shadow-lg shadow-premium-purple/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                            title="Mes Cours">
                            <i class="fas fa-book-open text-lg w-6 text-center"></i>
                            <span :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
                                class="font-bold transition-all duration-300 whitespace-nowrap overflow-hidden">Mes
                                Cours</span>
                        </a>
                        @if(auth()->user()->hasRole('student'))
                            <a href="{{ route('payments') }}"
                                class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('payments*') ? 'bg-premium-green text-white shadow-lg shadow-premium-green/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                                title="Mes Paiements">
                                <i class="fas fa-wallet text-lg w-6 text-center"></i>
                                <span :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
                                    class="font-bold transition-all duration-300 whitespace-nowrap overflow-hidden">Mes
                                    Paiements</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Management Section -->
                @if(auth()->user()->hasAnyRole(['admin', 'comptable', 'scolarite']))
                    <div>
                        <p :class="sidebarOpen ? 'opacity-100' : 'opacity-0'"
                            class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 transition-opacity duration-300 whitespace-nowrap">
                            Gestion</p>
                        <div class="space-y-2">
                            @if(auth()->user()->hasAnyRole(['admin', 'comptable']))
                                <a href="{{ route('admin.reconciliation.index') }}"
                                    class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.reconciliation.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                                    title="Rapprochement">
                                    <i class="fas fa-sync-alt text-lg w-6 text-center"></i>
                                    <span :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
                                        class="font-bold transition-all duration-300 whitespace-nowrap overflow-hidden">Rapprochement</span>
                                </a>
                            @endif

                            @if(auth()->user()->hasAnyRole(['admin', 'scolarite']))
                                <a href="{{ route('admin.students.index') }}"
                                    class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.students.*') ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                                    title="Étudiants">
                                    <i class="fas fa-user-graduate text-lg w-6 text-center"></i>
                                    <span :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
                                        class="font-bold transition-all duration-300 whitespace-nowrap overflow-hidden">Étudiants</span>
                                </a>
                            @endif

                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.users.index') }}"
                                    class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.users.*') ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                                    title="Utilisateurs">
                                    <i class="fas fa-users-cog text-lg w-6 text-center"></i>
                                    <span :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
                                        class="font-bold transition-all duration-300 whitespace-nowrap overflow-hidden">Utilisateurs</span>
                                </a>

                                <a href="{{ route('admin.reports.index') }}"
                                    class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.reports.*') ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                                    title="Signalements">
                                    <i class="fas fa-flag text-lg w-6 text-center"></i>
                                    <span :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
                                        class="font-bold transition-all duration-300 whitespace-nowrap overflow-hidden">Signalements</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Shared Links -->
                <div>
                    <p :class="sidebarOpen ? 'opacity-100' : 'opacity-0'"
                        class="px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 transition-opacity duration-300 whitespace-nowrap">
                        Documents</p>
                    <div class="space-y-2">
                        <a href="{{ route('convocations.index') }}"
                            class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('convocations.*') ? 'bg-purple-500 text-white shadow-lg shadow-purple-500/20' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                            title="Convocations">
                            <i class="fas fa-file-contract text-lg w-6 text-center"></i>
                            <span :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
                                class="font-bold transition-all duration-300 whitespace-nowrap overflow-hidden">Convocations</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- User Profile (Bottom) -->
            <div class="p-4 border-t border-white/5">
                <!-- User Profile (Bottom) -->
                <div class="p-4 border-t border-white/5" x-data="{ userMenuOpen: false }">
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen"
                            class="w-full glass rounded-2xl p-3 flex items-center gap-3 overflow-hidden hover:bg-white/5 transition-colors text-left group">
                            <div
                                class="w-10 h-10 shrink-0 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black shadow-lg group-hover:scale-105 transition-transform">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'"
                                class="flex-1 min-w-0 transition-all duration-300">
                                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-500 truncate uppercase tracking-widest">
                                    {{ Auth::user()->roles->first()->name ?? 'Utilisateur' }}
                                </p>
                            </div>
                            <i :class="sidebarOpen ? 'opacity-100' : 'opacity-0'"
                                class="fas fa-chevron-up text-xs text-slate-500 transition-opacity duration-300"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userMenuOpen" @click.away="userMenuOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                            x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                            class="absolute bottom-full left-0 w-full mb-2 bg-[#0A0B3B] border border-white/10 rounded-2xl shadow-xl overflow-hidden z-50">

                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors">
                                    <i class="fas fa-user-circle w-5 text-center"></i>
                                    <span class="font-medium">Mon Profil</span>
                                </a>
                                <a href="{{ route('admin.messages.index') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors {{ request()->routeIs('admin.messages.*') ? 'bg-white/5' : '' }}">
                                    <i class="fas fa-comments w-5 text-center"></i>
                                    <span class="font-medium">Messagerie</span>
                                </a>
                                <a href="{{ route('admin.reports.index') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-white/5' : '' }}">
                                    <i class="fas fa-flag w-5 text-center"></i>
                                    <span class="font-medium">Signalements</span>
                                </a>

                                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                    @csrf
                                    <button type="button" @click="logoutModalOpen = true; userMenuOpen = false"
                                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-rose-400 hover:bg-rose-500/10 transition-colors text-left">
                                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                                        <span class="font-bold">Déconnexion</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen min-w-0 transition-all duration-300">
            @include('layouts.navigation')

            <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
                @if (isset($header))
                    <div class="mb-8">
                        {{ $header }}
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <footer class="py-8 px-6 lg:px-8 border-t border-white/5 text-center lg:text-left">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-slate-600 text-xs font-bold uppercase tracking-[0.2em]">
                        &copy; 2025 EduPass-MG. Excellence & Innovation.
                    </p>
                    <div class="flex gap-4 text-xs text-slate-500 font-medium">
                        <a href="#" class="hover:text-white transition-colors">Aide</a>
                        <a href="#" class="hover:text-white transition-colors">Support</a>
                        <span class="text-slate-700">v1.2.0</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div x-show="logoutModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <!-- Backdrop -->
        <div x-show="logoutModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="logoutModalOpen = false">
        </div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="logoutModalOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-3xl bg-premium-dark/90 backdrop-blur-2xl border border-white/10 text-center shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-[320px] p-6">

                <div
                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-rose-500/20 to-rose-600/5 mb-4 shadow-lg shadow-rose-500/10">
                    <i class="fas fa-sign-out-alt text-lg text-rose-500"></i>
                </div>

                <h3 class="text-base font-black text-white mb-1" id="modal-title">Déconnexion</h3>
                <p class="text-[11px] text-slate-400 leading-relaxed mb-6">
                    Êtes-vous sûr de vouloir quitter votre session ?
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button"
                        class="w-full justify-center rounded-xl bg-white/5 px-3 py-2.5 text-xs font-bold text-slate-300 hover:bg-white/10 hover:text-white transition-all"
                        @click="logoutModalOpen = false">
                        Annuler
                    </button>
                    <button type="button" onclick="document.getElementById('logout-form').submit()"
                        class="w-full justify-center rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 px-3 py-2.5 text-xs font-bold text-white shadow-lg shadow-rose-500/20 hover:shadow-rose-500/40 hover:scale-[1.02] transition-all">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Global Chat Bubble -->
    @include('components.chat-bubble')
</body>

</html>