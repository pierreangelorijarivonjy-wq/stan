<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <div
                class="mx-auto w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-indigo-500/20">
                <i class="fas fa-envelope-open-text text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black text-white tracking-tight">Vérifiez votre email</h2>
            <p class="text-slate-500 mt-2 font-medium">Un lien de vérification vous a été envoyé.</p>
        </div>

        <div class="glass p-6 rounded-2xl border border-white/10 text-center">
            <p class="text-slate-300 text-sm leading-relaxed">
                Merci de votre inscription ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur
                le lien que nous venons de vous envoyer.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div
                class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm text-center font-medium">
                <i class="fas fa-check-circle mr-2"></i>
                Un nouveau lien de vérification a été envoyé.
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group">
                    <span>Renvoyer l'email</span>
                    <i class="fas fa-redo text-sm group-hover:rotate-180 transition-transform duration-500"></i>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-center text-sm font-bold text-slate-500 hover:text-white transition-colors underline decoration-2 underline-offset-8 decoration-white/10 hover:decoration-white/30">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>