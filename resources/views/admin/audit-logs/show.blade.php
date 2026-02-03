<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i class="fas fa-search text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Détails du Log') }} <span class="text-slate-500">#{{ $auditLog->id }}</span>
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Analyse approfondie de l'événement d'audit.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.audit-logs.index') }}"
                    class="inline-flex items-center gap-2 bg-white/5 border border-white/10 text-white px-6 py-3 rounded-2xl font-black hover:bg-white/10 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations Générales -->
            <div class="lg:col-span-2 space-y-6">
                <div class="relative group">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-slate-700 to-slate-800 rounded-[2.5rem] opacity-10 blur transition duration-500">
                    </div>
                    <div class="relative glass p-8 rounded-[2.5rem] border border-white/5">
                        <h3 class="text-lg font-black text-white mb-8 flex items-center gap-3">
                            <i class="fas fa-info-circle text-indigo-400"></i>
                            Informations Générales
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Date
                                        & Heure</p>
                                    <p class="text-sm font-bold text-white">
                                        {{ $auditLog->created_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                                <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">
                                        Utilisateur</p>
                                    <div class="flex items-center gap-3 mt-1">
                                        <div
                                            class="w-6 h-6 rounded bg-indigo-500/20 flex items-center justify-center text-indigo-400 text-[10px] font-black">
                                            {{ substr($auditLog->user ? $auditLog->user->name : 'S', 0, 1) }}
                                        </div>
                                        <p class="text-sm font-bold text-white">
                                            {{ $auditLog->user ? $auditLog->user->name : 'Système' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">
                                        Événement</p>
                                    <span
                                        class="inline-block mt-1 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                        {{ $auditLog->event }}
                                    </span>
                                </div>
                                <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">
                                        Cible (Auditable)</p>
                                    <p class="text-sm font-bold text-white">
                                        {{ class_basename($auditLog->auditable_type) }} <span
                                            class="text-slate-500 font-mono ml-2">#{{ $auditLog->auditable_id }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Changements -->
                @if($auditLog->changes)
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-[2.5rem] opacity-10 blur transition duration-500">
                        </div>
                        <div class="relative glass rounded-[2.5rem] border border-white/5 overflow-hidden">
                            <div class="px-8 py-6 bg-white/5 border-b border-white/5">
                                <h3 class="text-lg font-black text-white flex items-center gap-3">
                                    <i class="fas fa-exchange-alt text-indigo-400"></i>
                                    Changements Détectés
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                                            <th class="px-8 py-6">Champ</th>
                                            <th class="px-8 py-6">Ancienne Valeur</th>
                                            <th class="px-8 py-6">Nouvelle Valeur</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                        @foreach($auditLog->changes as $field => $change)
                                            <tr class="hover:bg-white/[0.02] transition-colors">
                                                <td class="px-8 py-6">
                                                    <span
                                                        class="text-xs font-black text-white uppercase tracking-widest">{{ $field }}</span>
                                                </td>
                                                <td class="px-8 py-6">
                                                    <div
                                                        class="text-xs font-bold text-rose-400 bg-rose-500/10 px-3 py-2 rounded-xl border border-rose-500/20 break-all">
                                                        {{ is_array($change['old']) ? json_encode($change['old']) : ($change['old'] ?: 'NULL') }}
                                                    </div>
                                                </td>
                                                <td class="px-8 py-6">
                                                    <div
                                                        class="text-xs font-bold text-emerald-400 bg-emerald-500/10 px-3 py-2 rounded-xl border border-emerald-500/20 break-all">
                                                        {{ is_array($change['new']) ? json_encode($change['new']) : ($change['new'] ?: 'NULL') }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @elseif($auditLog->new_values)
                    <div class="relative group">
                        <div
                            class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-[2.5rem] opacity-10 blur transition duration-500">
                        </div>
                        <div class="relative glass p-8 rounded-[2.5rem] border border-white/5">
                            <h3 class="text-lg font-black text-white mb-8 flex items-center gap-3">
                                <i class="fas fa-database text-indigo-400"></i>
                                Données Enregistrées
                            </h3>
                            <div class="bg-slate-900/50 rounded-2xl p-6 border border-white/5 overflow-x-auto">
                                <pre
                                    class="text-xs font-mono text-indigo-300 leading-relaxed">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <div class="glass p-8 rounded-[2.5rem] border border-white/5">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fas fa-laptop text-indigo-400"></i>
                        Contexte Technique
                    </h3>
                    <div class="space-y-6">
                        <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Adresse IP
                            </p>
                            <p class="text-sm font-mono font-bold text-white">{{ $auditLog->ip_address }}</p>
                        </div>
                        <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">User Agent
                            </p>
                            <p class="text-[10px] font-medium text-slate-400 leading-relaxed break-all">
                                {{ $auditLog->user_agent }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-indigo-500/10 to-purple-600/10 p-8 rounded-[2.5rem] border border-indigo-500/20">
                    <div class="flex items-center gap-4 mb-4">
                        <i class="fas fa-shield-check text-indigo-400"></i>
                        <span class="text-xs font-black text-white uppercase tracking-widest">Audit Certifié</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">
                        Cet enregistrement est immuable et sert de preuve légale pour toutes les opérations effectuées
                        sur le système.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>