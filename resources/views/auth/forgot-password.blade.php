<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <div
                class="mx-auto w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-indigo-500/20">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black text-white tracking-tight">Mot de passe oublié ?</h2>
            <p class="text-slate-500 mt-2 font-medium">Entrez votre email pour recevoir un lien de réinitialisation.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email"
                    class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Email</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-envelope text-slate-600 text-sm group-focus-within:text-indigo-400 transition-colors"></i>
                    </div>
                    <input id="email"
                        class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium"
                        type="email" name="email" :value="old('email')" required autofocus
                        placeholder="votre@email.mg" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group">
                    <span>Envoyer le lien</span>
                    <i
                        class="fas fa-paper-plane text-sm group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                </button>
            </div>

            <div class="text-center pt-8 border-t border-white/5">
                <a href="{{ route('login') }}"
                    class="text-sm text-slate-500 hover:text-white font-bold transition-colors inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Retour à la connexion
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>