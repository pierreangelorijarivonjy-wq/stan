<section>
    <header class="mb-8">
        <h2 class="text-2xl font-black text-white flex items-center gap-3">
            <i class="fas fa-shield-alt text-purple-400"></i>
            {{ __('Mise à jour du Mot de Passe') }}
        </h2>

        <p class="mt-2 text-sm text-slate-500 font-medium">
            {{ __('Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester en sécurité.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-2">
            <x-input-label for="update_password_current_password" :value="__('Mot de passe actuel')"
                class="text-slate-400 font-bold text-xs uppercase tracking-widest" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock-open text-slate-600 text-sm"></i>
                </div>
                <input id="update_password_current_password" name="current_password" type="password"
                    class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all font-medium"
                    autocomplete="off" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password" :value="__('Nouveau mot de passe')"
                class="text-slate-400 font-bold text-xs uppercase tracking-widest" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-key text-slate-600 text-sm"></i>
                </div>
                <input id="update_password_password" name="password" type="password"
                    class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all font-medium"
                    autocomplete="off" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="space-y-2">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le mot de passe')"
                class="text-slate-400 font-bold text-xs uppercase tracking-widest" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-check-double text-slate-600 text-sm"></i>
                </div>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                    class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all font-medium"
                    autocomplete="off" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit"
                class="px-8 py-3 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl font-black shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 hover:-translate-y-0.5 transition-all">
                {{ __('Enregistrer') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-400 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    {{ __('Enregistré.') }}
                </p>
            @endif
        </div>
    </form>
</section>