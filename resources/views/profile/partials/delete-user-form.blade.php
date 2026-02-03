<section class="space-y-8">
    <header>
        <h2 class="text-2xl font-black text-white flex items-center gap-3">
            <i class="fas fa-user-slash text-rose-400"></i>
            {{ __('Supprimer le Compte') }}
        </h2>

        <p class="mt-2 text-sm text-slate-500 font-medium leading-relaxed">
            {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger toutes les données ou informations que vous souhaitez conserver.') }}
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-8 py-3 bg-rose-500/10 border border-rose-500/20 text-rose-500 rounded-xl font-black hover:bg-rose-500 hover:text-white transition-all">
        {{ __('Supprimer le compte') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}"
            class="p-10 bg-[#1E293B] border border-white/10 rounded-[2.5rem] shadow-2xl">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-black text-white mb-4">
                {{ __('Êtes-vous sûr de vouloir supprimer votre compte ?') }}
            </h2>

            <p class="text-sm text-slate-400 font-medium leading-relaxed mb-8">
                {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez saisir votre mot de passe pour confirmer que vous souhaitez supprimer définitivement votre compte.') }}
            </p>

            <div class="space-y-2">
                <x-input-label for="password" value="{{ __('Mot de passe') }}" class="sr-only" />

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-slate-600 text-sm"></i>
                    </div>
                    <input id="password" name="password" type="password"
                        class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all font-medium"
                        placeholder="{{ __('Mot de passe') }}" autocomplete="off" />
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-10 flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-6 py-3 text-sm font-bold text-slate-400 hover:text-white transition-colors">
                    {{ __('Annuler') }}
                </button>

                <button type="submit"
                    class="px-8 py-3 bg-rose-600 text-white rounded-xl font-black shadow-lg shadow-rose-600/20 hover:shadow-rose-600/40 hover:-translate-y-0.5 transition-all">
                    {{ __('Supprimer définitivement') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>