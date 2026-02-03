<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <div
                class="mx-auto w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-indigo-500/20">
                <i class="fas fa-shield-alt text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black text-white tracking-tight">Zone Sécurisée</h2>
            <p class="text-slate-500 mt-2 font-medium">Confirmation requise pour changer de compte</p>
        </div>

        <div class="glass p-6 rounded-2xl border border-white/10 text-center">
            <p class="text-slate-300 text-sm leading-relaxed">
                Veuillez confirmer votre mot de passe pour accéder au compte <span
                    class="font-black text-white">{{ $user->name }}</span>.
            </p>
        </div>

        @if ($errors->any())
            <div
                class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-sm text-center font-medium">
                <i class="fas fa-exclamation-circle mr-2"></i>
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('account.switch.verify') }}" class="space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Mot de
                    passe</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-lock text-slate-600 text-sm group-focus-within:text-indigo-400 transition-colors"></i>
                    </div>
                    <input id="password"
                        class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium"
                        type="password" name="password" required autofocus autocomplete="off" placeholder="••••••••" />
                </div>
            </div>

            <button type="submit"
                class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group">
                <span>Confirmer et Continuer</span>
                <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
            </button>
        </form>

        <div class="text-center pt-8 border-t border-white/5">
            <p class="text-[10px] text-slate-500 font-black uppercase tracking-[0.2em]">
                🔒 Vérification de sécurité EduPass-MG
            </p>
        </div>
    </div>
</x-guest-layout>