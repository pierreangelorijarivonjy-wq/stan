<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <div
                class="mx-auto w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-indigo-500/20">
                <i class="fas fa-shield-check text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black text-white tracking-tight">Vérification</h2>
            <p class="text-slate-500 mt-2 font-medium">Sécurisez votre accès avec un code</p>
        </div>

        @php
            $user = null;
            if (session()->has('auth.2fa_user_id')) {
                $user = \App\Models\User::find(session('auth.2fa_user_id'));
            }
            $isStaff = $user && $user->hasAnyRole(['admin', 'comptable', 'scolarite', 'controleur']);
        @endphp

        <div id="code-instruction" class="glass p-6 rounded-2xl border border-white/10 text-center">
            <p class="text-slate-300 text-sm leading-relaxed">
                @if($isStaff)
                    Veuillez entrer votre <span class="font-black text-white">matricule</span> pour confirmer votre
                    identité.
                @else
                    Veuillez entrer le <span class="font-black text-white">code de sécurité</span> à 6 chiffres reçu par
                    email.
                @endif
            </p>
        </div>

        <div id="recovery-instruction" class="glass p-6 rounded-2xl border border-white/10 text-center hidden">
            <p class="text-slate-300 text-sm leading-relaxed">
                Veuillez entrer l'un de vos <span class="font-black text-white">codes de récupération</span> d'urgence.
            </p>
        </div>

        @if (session('status') == 'code-sent')
            <div
                class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm text-center font-medium">
                <i class="fas fa-check-circle mr-2"></i>
                Un nouveau code de sécurité a été envoyé.
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-sm text-center font-medium">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-6">
            @csrf

            <!-- Authenticator Code -->
            <div id="code-group" class="space-y-2">
                <label for="code"
                    class="block text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Code de
                    sécurité</label>
                <div class="relative group">
                    <input id="code" name="code" type="text"
                        class="block w-full px-4 py-5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-center text-2xl font-black tracking-widest"
                        placeholder="{{ $isStaff ? 'VOTRE-MATRICULE' : '000000' }}" autofocus
                        autocomplete="one-time-code">
                </div>
                <x-input-error :messages="$errors->get('code')" class="mt-2 text-center" />
            </div>

            <!-- Recovery Code -->
            <div id="recovery-group" class="space-y-2 hidden">
                <label for="recovery_code"
                    class="block text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Code de
                    récupération</label>
                <div class="relative group">
                    <input id="recovery_code" name="recovery_code" type="text"
                        class="block w-full px-4 py-5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-center font-mono"
                        placeholder="abcdef-123456" autocomplete="off">
                </div>
                <x-input-error :messages="$errors->get('recovery_code')" class="mt-2 text-center" />
            </div>

            <button type="submit"
                class="w-full py-4 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20 hover:shadow-indigo-500/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group">
                <span>Vérifier</span>
                <i class="fas fa-check-circle text-sm group-hover:scale-110 transition-transform"></i>
            </button>

            <div class="flex flex-col items-center space-y-2 pt-1">
                <button type="button" id="toggle-recovery" onclick="toggleRecovery()"
                    class="text-sm font-bold text-slate-500 hover:text-white transition-colors underline decoration-2 underline-offset-8 decoration-white/10 hover:decoration-white/30">
                    {{ __('Utiliser un code de récupération') }}
                </button>
            </div>
        </form>

        <div class="mt-4 pt-8 border-t border-white/5 text-center">
            <form method="POST" action="{{ route('two-factor.email-code') }}">
                @csrf
                <button type="submit"
                    class="text-sm font-bold text-slate-500 hover:text-white transition-colors flex items-center justify-center gap-2 mx-auto group">
                    <i class="fas fa-redo text-xs group-hover:rotate-180 transition-transform duration-500"></i>
                    {{ __('Renvoyer le code par email') }}
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleRecovery() {
            const codeGroup = document.getElementById('code-group');
            const recoveryGroup = document.getElementById('recovery-group');
            const codeInstruction = document.getElementById('code-instruction');
            const recoveryInstruction = document.getElementById('recovery-instruction');
            const toggleBtn = document.getElementById('toggle-recovery');
            const codeInput = document.getElementById('code');
            const recoveryInput = document.getElementById('recovery_code');

            if (codeGroup.classList.contains('hidden')) {
                codeGroup.classList.remove('hidden');
                codeInstruction.classList.remove('hidden');
                recoveryGroup.classList.add('hidden');
                recoveryInstruction.classList.add('hidden');
                toggleBtn.innerText = "{{ __('Utiliser un code de récupération') }}";
                codeInput.required = true;
                recoveryInput.required = false;
                recoveryInput.value = '';
            } else {
                codeGroup.classList.add('hidden');
                codeInstruction.classList.add('hidden');
                recoveryGroup.classList.remove('hidden');
                recoveryInstruction.classList.remove('hidden');
                toggleBtn.innerText = "{{ __('Utiliser un code de sécurité') }}";
                codeInput.required = false;
                codeInput.value = '';
                recoveryInput.required = true;
            }
        }
    </script>
</x-guest-layout>