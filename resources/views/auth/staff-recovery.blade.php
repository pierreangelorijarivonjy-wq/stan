<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <div
                class="mx-auto w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-amber-500/20">
                <i class="fas fa-user-cog text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-black text-white tracking-tight">Récupération Staff</h2>
            <p class="text-slate-500 mt-2 font-medium">Réinitialisez votre accès institutionnel</p>
        </div>

        <div class="glass p-6 rounded-2xl border border-white/10 text-center">
            <p class="text-slate-300 text-sm leading-relaxed">
                Veuillez entrer votre email institutionnel et votre matricule pour réinitialiser votre mot de passe.
            </p>
        </div>

        <form method="POST" action="{{ route('staff.recovery.store') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Email
                    Institutionnel</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-envelope text-slate-600 text-sm group-focus-within:text-amber-400 transition-colors"></i>
                    </div>
                    <input id="email"
                        class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all font-medium"
                        type="email" name="email" :value="old('email')" required autofocus
                        placeholder="nom@edupass.mg" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Matricule -->
            <div class="space-y-2">
                <label for="matricule"
                    class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Matricule</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-id-card text-slate-600 text-sm group-focus-within:text-amber-400 transition-colors"></i>
                    </div>
                    <input id="matricule"
                        class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all font-medium"
                        type="text" name="matricule" :value="old('matricule')" required placeholder="ADM-UF-2025-001" />
                </div>
                <x-input-error :messages="$errors->get('matricule')" class="mt-2" />
            </div>

            <!-- New Password -->
            <div class="space-y-2">
                <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Nouveau
                    mot de passe</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-lock text-slate-600 text-sm group-focus-within:text-emerald-400 transition-colors"></i>
                    </div>
                    <input id="password"
                        class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-medium"
                        type="password" name="password" required autocomplete="off" placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation"
                    class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Confirmer le mot de
                    passe</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-shield-check text-slate-600 text-sm group-focus-within:text-emerald-400 transition-colors"></i>
                    </div>
                    <input id="password_confirmation"
                        class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-medium"
                        type="password" name="password_confirmation" required autocomplete="off"
                        placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full py-4 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-black rounded-2xl shadow-xl shadow-amber-500/20 hover:shadow-amber-500/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 group">
                    <span>Réinitialiser le mot de passe</span>
                    <i class="fas fa-check-circle text-sm group-hover:scale-110 transition-transform"></i>
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>