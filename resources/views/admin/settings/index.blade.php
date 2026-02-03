<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i class="fas fa-cogs text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Paramètres Système') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Configurez les moteurs, les services et les
                        modules de la plateforme.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-10">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- SMTP Settings -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 ml-2">
                        <div
                            class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                            <i class="fas fa-envelope text-sm"></i>
                        </div>
                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Configuration SMTP</h3>
                    </div>
                    <div class="glass p-8 rounded-[2.5rem] border border-white/5 space-y-6">
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Serveur
                                SMTP</label>
                            <input type="text" name="mail_host"
                                value="{{ \App\Models\Setting::get('mail_host', config('mail.mailers.smtp.host')) }}"
                                class="block w-full px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium text-sm">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Port</label>
                            <input type="text" name="mail_port"
                                value="{{ \App\Models\Setting::get('mail_port', config('mail.mailers.smtp.port')) }}"
                                class="block w-full px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium text-sm">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Utilisateur</label>
                            <input type="text" name="mail_username"
                                value="{{ \App\Models\Setting::get('mail_username', config('mail.mailers.smtp.username')) }}"
                                class="block w-full px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium text-sm">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Mot
                                de passe</label>
                            <input type="password" name="mail_password"
                                value="{{ \App\Models\Setting::get('mail_password', config('mail.mailers.smtp.password')) }}"
                                class="block w-full px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium text-sm">
                        </div>
                    </div>
                </div>

                <!-- API Settings -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 ml-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-400">
                            <i class="fas fa-key text-sm"></i>
                        </div>
                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Clés API & Services</h3>
                    </div>
                    <div class="glass p-8 rounded-[2.5rem] border border-white/5 space-y-6">
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">MVola
                                API Key</label>
                            <input type="text" name="api_mvola_key"
                                value="{{ \App\Models\Setting::get('api_mvola_key') }}"
                                class="block w-full px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all font-medium text-sm">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Orange
                                Money API</label>
                            <input type="text" name="api_orange_key"
                                value="{{ \App\Models\Setting::get('api_orange_key') }}"
                                class="block w-full px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all font-medium text-sm">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-4">Airtel
                                Money API</label>
                            <input type="text" name="api_airtel_key"
                                value="{{ \App\Models\Setting::get('api_airtel_key') }}"
                                class="block w-full px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all font-medium text-sm">
                        </div>
                    </div>
                </div>

                <!-- Modules Settings -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 ml-2">
                        <div
                            class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                            <i class="fas fa-cubes text-sm"></i>
                        </div>
                        <h3 class="text-sm font-black text-white uppercase tracking-widest">Activation des Modules</h3>
                    </div>
                    <div class="glass p-8 rounded-[2.5rem] border border-white/5 space-y-4">
                        <div
                            class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5 group hover:bg-white/10 transition-all">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white">Module Cours</span>
                                <span class="text-[10px] text-slate-500 font-medium">Accès aux ressources
                                    pédagogiques</span>
                            </div>
                            <select name="module_courses"
                                class="bg-slate-900 border-white/10 rounded-lg text-xs font-black text-indigo-400 focus:ring-indigo-500 cursor-pointer">
                                <option value="enabled" {{ \App\Models\Setting::get('module_courses', 'enabled') === 'enabled' ? 'selected' : '' }}>Activé</option>
                                <option value="disabled" {{ \App\Models\Setting::get('module_courses') === 'disabled' ? 'selected' : '' }}>Désactivé</option>
                            </select>
                        </div>
                        <div
                            class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5 group hover:bg-white/10 transition-all">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white">Module Convocations</span>
                                <span class="text-[10px] text-slate-500 font-medium">Génération et scan des
                                    convocations</span>
                            </div>
                            <select name="module_convocations"
                                class="bg-slate-900 border-white/10 rounded-lg text-xs font-black text-indigo-400 focus:ring-indigo-500 cursor-pointer">
                                <option value="enabled" {{ \App\Models\Setting::get('module_convocations', 'enabled') === 'enabled' ? 'selected' : '' }}>Activé</option>
                                <option value="disabled" {{ \App\Models\Setting::get('module_convocations') === 'disabled' ? 'selected' : '' }}>Désactivé</option>
                            </select>
                        </div>
                        <div
                            class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5 group hover:bg-white/10 transition-all">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-white">Rapprochement Auto</span>
                                <span class="text-[10px] text-slate-500 font-medium">Validation automatique des
                                    paiements</span>
                            </div>
                            <select name="module_auto_reconciliation"
                                class="bg-slate-900 border-white/10 rounded-lg text-xs font-black text-indigo-400 focus:ring-indigo-500 cursor-pointer">
                                <option value="enabled" {{ \App\Models\Setting::get('module_auto_reconciliation', 'enabled') === 'enabled' ? 'selected' : '' }}>Activé</option>
                                <option value="disabled" {{ \App\Models\Setting::get('module_auto_reconciliation') === 'disabled' ? 'selected' : '' }}>Désactivé</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-10 border-t border-white/5">
                <button type="submit"
                    class="bg-indigo-500 text-white px-12 py-4 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20 flex items-center gap-3">
                    <i class="fas fa-save"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</x-app-layout>