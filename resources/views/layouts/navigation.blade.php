<nav x-data="{ open: false }"
    class="bg-premium-dark/80 backdrop-blur-xl border-b border-white/5 sticky top-0 z-40 transition-all duration-300">

    @if(session()->has('impersonate'))
        <div
            class="bg-gradient-to-r from-amber-500 to-rose-500 text-white py-2 px-4 text-center text-xs font-black uppercase tracking-widest flex items-center justify-center gap-4">
            <i class="fas fa-user-secret animate-pulse"></i>
            <span>Mode Usurpation : Vous êtes connecté en tant que {{ Auth::user()->name }}</span>
            <a href="{{ route('admin.stop-impersonating') }}"
                class="bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg transition-all border border-white/20">
                Arrêter l'usurpation
            </a>
        </div>
    @endif

    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-8 flex-1">
                <!-- Mobile Logo (Hidden on Desktop) -->
                <div class="shrink-0 flex items-center lg:hidden">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-premium-cyan to-premium-purple rounded-lg flex items-center justify-center text-white shadow-lg">
                            <i class="fas fa-graduation-cap text-sm"></i>
                        </div>
                        <span class="font-heading font-black text-lg tracking-tight text-white">EduPass<span
                                class="text-premium-cyan">-MG</span></span>
                    </a>
                </div>

                <!-- Search Bar (Desktop) -->
                <div class="hidden lg:flex flex-1 max-w-xl">
                    <div class="relative w-full group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i
                                class="fas fa-search text-slate-500 group-focus-within:text-premium-cyan transition-colors"></i>
                        </div>
                        <form action="{{ route('dashboard') }}" method="GET">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="block w-full pl-11 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-premium-cyan focus:border-transparent transition-all font-medium text-sm"
                                placeholder="Rechercher un étudiant, un paiement, une session..." autocomplete="off">
                        </form>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span
                                class="text-xs text-slate-600 font-mono border border-white/10 rounded px-1.5 py-0.5">/</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side Actions -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">

                <!-- Notifications (Mockup) -->
                <button
                    class="relative p-2 text-slate-400 hover:text-white transition-colors rounded-xl hover:bg-white/5 group">
                    <i class="far fa-bell text-lg"></i>
                    <span
                        class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-premium-dark"></span>
                </button>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-3 px-3 py-2 border border-white/5 rounded-xl bg-white/5 text-sm font-bold text-slate-300 hover:text-white hover:bg-white/10 transition-all group">
                            <div
                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-premium-sidebar to-premium-purple flex items-center justify-center text-white text-xs font-black shadow-lg group-hover:scale-110 transition-transform">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden md:block">{{ Auth::user()->name }}</span>
                            <i
                                class="fas fa-chevron-down text-[10px] text-slate-500 group-hover:text-white transition-colors"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-premium-sidebar border border-white/10 rounded-xl overflow-hidden shadow-2xl">
                            <div class="px-4 py-3 border-b border-white/5">
                                <p class="text-sm text-white font-bold">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <x-dropdown-link :href="route('profile.edit')"
                                class="text-slate-300 hover:bg-white/5 hover:text-white transition-colors py-2.5 px-4 flex items-center gap-2">
                                <i class="fas fa-user-circle text-premium-cyan w-5 text-center"></i>
                                {{ __('Mon Profil') }}
                            </x-dropdown-link>

                            <div class="border-t border-white/5 my-1"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-rose-400 hover:bg-rose-500/10 transition-colors py-2.5 px-4 flex items-center gap-2">
                                    <i class="fas fa-sign-out-alt w-5 text-center"></i> {{ __('Se déconnecter') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden gap-4">
                <!-- Mobile Search Toggle -->
                <button class="p-2 text-slate-400 hover:text-white">
                    <i class="fas fa-search text-lg"></i>
                </button>

                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                    <i :class="{'hidden': open, 'block': ! open }" class="fas fa-bars text-xl"></i>
                    <i :class="{'hidden': ! open, 'block': open }" class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}"
        class="hidden lg:hidden bg-premium-dark border-t border-white/5 overflow-y-auto max-h-[calc(100vh-5rem)]">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="text-slate-300 hover:text-white font-bold rounded-lg">
                <i class="fas fa-th-large mr-3 text-premium-cyan"></i> {{ __('Tableau de bord') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('home')" class="text-slate-300 hover:text-white font-bold rounded-lg">
                <i class="fas fa-home mr-3 text-premium-cyan"></i> {{ __('Accueil Site') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('courses')" :active="request()->routeIs('courses*')"
                class="text-slate-300 hover:text-white font-bold rounded-lg">
                <i class="fas fa-book-open mr-3 text-premium-purple"></i> {{ __('Mes Cours') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/5 px-4">
            <div class="flex items-center gap-3 mb-3 px-2">
                <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-premium-sidebar to-premium-purple flex items-center justify-center text-white font-black shadow-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-white">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                    class="text-slate-300 hover:text-white font-bold rounded-lg">
                    <i class="fas fa-user-circle mr-3 text-premium-cyan"></i> {{ __('Mon Profil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="text-rose-400 hover:bg-rose-500/10 font-bold rounded-lg">
                        <i class="fas fa-sign-out-alt mr-3"></i> {{ __('Se déconnecter') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>