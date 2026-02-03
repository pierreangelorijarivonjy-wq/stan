<!DOCTYPE html>
<html lang="fr">
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement de Compte - EduPass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Dark Mode Gradient */
        .dark body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            color: #f8fafc;
        }

        /* Light Mode Background */
        body {
            background-color: #f3f4f6;
            color: #1f2937;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Glass Card - Dark Mode */
        .dark .glass-card {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        /* Card - Light Mode */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(229, 231, 235, 1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .account-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .account-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="min-h-screen py-12 px-4 font-sans antialiased">
    <!-- Theme Toggle -->
    <div class="absolute top-4 right-4 z-50">
        <button id="theme-toggle" type="button"
            class="p-2 rounded-full bg-gray-200 dark:bg-slate-700 text-gray-500 dark:text-gray-400 hover:bg-gray-300 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 1 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z"
                    fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 0 016.707 2.707a8.001 0 1010.586 10.586z"></path>
            </svg>
        </button>
    </div>

    <div class="container mx-auto max-w-5xl">
        <div class="text-center mb-8 sm:mb-12">
            <h1
                class="text-3xl sm:text-5xl font-extrabold tracking-tight mb-4 drop-shadow-lg text-gray-900 dark:text-white transition-colors">
                🔄 Changement de Compte
            </h1>
            <p class="text-gray-600 dark:text-blue-100 text-lg max-w-2xl mx-auto transition-colors">
                Accédez instantanément aux différents rôles pour vos tests et démonstrations.
            </p>
        </div>

        @if(session('success'))
            <div
                class="max-w-md mx-auto bg-green-100 border border-green-200 text-green-800 dark:bg-green-500/20 dark:border-green-500/50 dark:text-green-100 px-6 py-4 rounded-2xl mb-8 backdrop-blur-md flex items-center gap-3 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div
                class="max-w-md mx-auto bg-red-100 border border-red-200 text-red-800 dark:bg-red-500/20 dark:border-red-500/50 dark:text-red-100 px-6 py-4 rounded-2xl mb-8 backdrop-blur-md flex items-center gap-3 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if(auth()->check())
            <div
                class="max-w-2xl mx-auto bg-white border border-gray-200 dark:bg-white/10 dark:border-white/20 p-6 rounded-3xl mb-12 backdrop-blur-md flex items-center justify-between transition-colors shadow-lg dark:shadow-none">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-blue-100 text-blue-600 dark:bg-blue-500 dark:text-white rounded-full flex items-center justify-center text-2xl shadow-inner transition-colors">
                        👤
                    </div>
                    <div>
                        <p
                            class="text-sm text-gray-500 dark:text-blue-200 uppercase tracking-widest font-bold transition-colors">
                            Connecté en tant que</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white transition-colors">
                            {{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-600 dark:text-blue-100 opacity-75 transition-colors">
                            {{ auth()->user()->email }}</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <span
                        class="px-4 py-1 bg-green-100 text-green-800 dark:bg-white/20 dark:text-white rounded-full text-xs font-bold uppercase tracking-tighter transition-colors">Session
                        Active</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Admin -->
            @if($accounts['admin'])
                <form action="{{ route('account.switch') }}" method="POST" class="account-card">
                    @csrf
                    <input type="hidden" name="email" value="admin@edupass.mg">
                    <button type="submit"
                        class="w-full h-full bg-white dark:bg-gradient-to-br dark:from-red-500/80 dark:to-red-700/80 p-6 sm:p-8 rounded-3xl border border-gray-200 dark:border-white/20 text-left relative overflow-hidden group shadow-lg dark:shadow-none transition-all">
                        <div
                            class="absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform text-gray-900 dark:text-white">
                            👨‍💼</div>
                        <div class="relative z-10">
                            <div class="text-4xl mb-4">👨‍💼</div>
                            <h3 class="text-2xl font-bold mb-1 text-gray-900 dark:text-white">Administrateur</h3>
                            <p class="text-sm text-gray-600 dark:text-red-100 opacity-80 mb-4">admin@edupass.mg</p>
                            <div
                                class="flex items-center text-xs font-bold uppercase tracking-wider gap-2 text-blue-600 dark:text-white">
                                <span>Accéder</span>
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </div>
                        </div>
                    </button>
                </form>
            @endif

            <!-- Comptable -->
            @if($accounts['comptable'])
                <form action="{{ route('account.switch') }}" method="POST" class="account-card">
                    @csrf
                    <input type="hidden" name="email" value="comptable@edupass.mg">
                    <button type="submit"
                        class="w-full h-full bg-white dark:bg-gradient-to-br dark:from-emerald-500/80 dark:to-emerald-700/80 p-6 sm:p-8 rounded-3xl border border-gray-200 dark:border-white/20 text-left relative overflow-hidden group shadow-lg dark:shadow-none transition-all">
                        <div
                            class="absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform text-gray-900 dark:text-white">
                            💰</div>
                        <div class="relative z-10">
                            <div class="text-4xl mb-4">💰</div>
                            <h3 class="text-2xl font-bold mb-1 text-gray-900 dark:text-white">Comptable</h3>
                            <p class="text-sm text-gray-600 dark:text-emerald-100 opacity-80 mb-4">comptable@edupass.mg</p>
                            <div
                                class="flex items-center text-xs font-bold uppercase tracking-wider gap-2 text-blue-600 dark:text-white">
                                <span>Accéder</span>
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </div>
                        </div>
                    </button>
                </form>
            @endif

            <!-- Scolarité -->
            @if($accounts['scolarite'])
                <form action="{{ route('account.switch') }}" method="POST" class="account-card">
                    @csrf
                    <input type="hidden" name="email" value="scolarite@edupass.mg">
                    <button type="submit"
                        class="w-full h-full bg-white dark:bg-gradient-to-br dark:from-purple-500/80 dark:to-purple-700/80 p-6 sm:p-8 rounded-3xl border border-gray-200 dark:border-white/20 text-left relative overflow-hidden group shadow-lg dark:shadow-none transition-all">
                        <div
                            class="absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform text-gray-900 dark:text-white">
                            📚</div>
                        <div class="relative z-10">
                            <div class="text-4xl mb-4">📚</div>
                            <h3 class="text-2xl font-bold mb-1 text-gray-900 dark:text-white">Scolarité</h3>
                            <p class="text-sm text-gray-600 dark:text-purple-100 opacity-80 mb-4">scolarite@edupass.mg</p>
                            <div
                                class="flex items-center text-xs font-bold uppercase tracking-wider gap-2 text-blue-600 dark:text-white">
                                <span>Accéder</span>
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </div>
                        </div>
                    </button>
                </form>
            @endif
        </div>

        <!-- Étudiants Section -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-3 text-gray-900 dark:text-white transition-colors">
                <span class="w-8 h-1 bg-blue-600 dark:bg-white rounded-full transition-colors"></span>
                Portail Étudiants
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($accounts['students'] as $student)
                    <form action="{{ route('account.switch') }}" method="POST" class="account-card">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $student->id }}">
                        <button type="submit"
                            class="w-full bg-white dark:bg-white/10 hover:bg-gray-50 dark:hover:bg-white/20 p-6 rounded-2xl border border-gray-200 dark:border-white/10 text-center transition-all group shadow-md dark:shadow-none">
                            <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🎓</div>
                            <p class="font-bold text-lg truncate text-gray-900 dark:text-white transition-colors">
                                {{ $student->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-blue-200 opacity-60 truncate transition-colors">
                                {{ $student->email }}</p>
                        </button>
                    </form>
                @endforeach
            </div>
        </div>

        <!-- Recovery Link -->
        <div class="max-w-md mx-auto mb-12">
            <a href="{{ route('staff.recovery') }}"
                class="block w-full p-6 bg-white dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10 border border-dashed border-gray-300 dark:border-white/30 rounded-3xl text-center transition-all group shadow-sm dark:shadow-none">
                <div class="text-2xl mb-2">🔑</div>
                <h3
                    class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-200 transition-colors">
                    Récupération Staff
                    (Matricule)</h3>
                <p class="text-sm text-gray-500 dark:text-blue-100 opacity-60 transition-colors">Utilisez votre
                    matricule pour récupérer l'accès</p>
            </a>
        </div>

        <!-- Footer Actions -->
        <div class="flex flex-col md:flex-row gap-4 justify-center items-center">
            <a href="{{ route('dashboard') }}"
                class="px-10 py-4 bg-blue-600 text-white dark:bg-white dark:text-blue-600 font-bold rounded-2xl shadow-xl hover:bg-blue-700 dark:hover:bg-blue-50 hover:scale-105 transition-all">
                📊 Retour au Dashboard
            </a>
            @if(auth()->check())
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-10 py-4 bg-red-100 hover:bg-red-200 border border-red-200 text-red-800 dark:bg-red-600/20 dark:hover:bg-red-600/40 dark:border-red-500/50 dark:text-red-100 font-bold rounded-2xl transition-all">
                        🚪 Se Déconnecter
                    </button>
                </form>
            @endif
        </div>

        <div class="mt-12 text-center">
            <p class="text-gray-500 dark:text-blue-100 text-sm opacity-50 transition-colors">
                EduPass-MG &bull; Système de Gestion Éducative &bull; 2025
            </p>
        </div>
    </div>

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function () {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (localStorage.getItem('theme')) {
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        });
    </script>
</body>


</html>