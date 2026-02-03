<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-slate-900">
            {{ __('Authentification à deux facteurs') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            {{ __('Ajoutez une sécurité supplémentaire à votre compte en utilisant l\'authentification à deux facteurs.') }}
        </p>
    </header>

    @if (auth()->user()->hasEnabledTwoFactorAuthentication())
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-emerald-800">
                    {{ __('L\'authentification à deux facteurs est activée.') }}
                </p>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">
                    {{ __('Codes de récupération') }}
                </h3>
                <p class="mt-1 text-sm text-slate-500">
                    {{ __('Conservez ces codes en lieu sûr. Ils peuvent être utilisés pour récupérer l\'accès à votre compte si vous perdez votre appareil d\'authentification.') }}
                </p>
            </div>

            <div
                class="grid grid-cols-2 gap-2 max-w-xl p-4 font-mono text-sm bg-slate-50 border border-slate-200 rounded-xl">
                @foreach (auth()->user()->recoveryCodes() as $code)
                    <div class="text-slate-700 select-all">{{ $code }}</div>
                @endforeach
            </div>

            <div class="flex items-center gap-4">
                <form method="POST" action="{{ route('two-factor.regenerate') }}">
                    @csrf
                    <x-secondary-button type="submit" class="bg-white border-slate-300 text-slate-700 hover:bg-slate-50">
                        {{ __('Régénérer les codes') }}
                    </x-secondary-button>
                </form>

                <form method="POST" action="{{ route('two-factor.disable') }}" x-data="{ confirming: false }">
                    @csrf
                    @method('DELETE')

                    <div class="flex items-center gap-4">
                        <template x-if="!confirming">
                            <x-danger-button type="button" @click="confirming = true">
                                {{ __('Désactiver le 2FA') }}
                            </x-danger-button>
                        </template>

                        <div x-show="confirming" class="flex flex-col space-y-3" x-cloak>
                            <x-text-input id="password" name="password" type="password"
                                class="block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl"
                                placeholder="{{ __('Mot de passe actuel') }}" required autocomplete="off" />
                            <div class="flex gap-2">
                                <x-danger-button type="submit">
                                    {{ __('Confirmer la désactivation') }}
                                </x-danger-button>
                                <x-secondary-button type="button" @click="confirming = false"
                                    class="bg-white border-slate-300 text-slate-700 hover:bg-slate-50">
                                    {{ __('Annuler') }}
                                </x-secondary-button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="mt-4">
            <form method="POST" action="{{ route('two-factor.enable') }}">
                @csrf
                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 border-none">
                    {{ __('Activer le 2FA') }}
                </x-primary-button>
            </form>
        </div>
    @endif
</section>