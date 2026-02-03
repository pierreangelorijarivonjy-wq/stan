<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <div
                class="mx-auto w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-blue-600/20">
                <i class="fas fa-user-shield text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black text-white tracking-tight">Portail Administratif</h2>
            <p class="text-slate-500 mt-2 font-medium">Accès réservé au personnel autorisé</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Email
                    Professionnel</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-envelope text-slate-600 text-sm group-focus-within:text-blue-400 transition-colors"></i>
                    </div>
                    <input id="email"
                        class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-medium"
                        type="email" name="email" value="" required autofocus autocomplete="off"
                        placeholder="nom@edupass.mg" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Mot de
                    passe</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-lock text-slate-600 text-sm group-focus-within:text-blue-400 transition-colors"></i>
                    </div>
                    <input id="password"
                        class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-medium"
                        type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-blue-600/20 hover:shadow-blue-600/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group">
                    <span>Connexion Administration</span>
                    <i class="fas fa-shield-check text-sm group-hover:scale-110 transition-transform"></i>
                </button>
            </div>

            <div class="text-center pt-8 border-t border-white/5">
                <a href="{{ route('login') }}"
                    class="text-sm text-slate-500 hover:text-white font-bold transition-colors inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Retour au portail étudiant
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>