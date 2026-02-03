<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Rapport de Rapprochement') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Vue d'ensemble des transactions et de la santé
                        financière.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.reconciliation.index') }}"
                    class="inline-flex items-center gap-2 bg-white/5 border border-white/10 text-white px-6 py-3 rounded-2xl font-black hover:bg-white/10 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Retour
                </a>
                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 bg-indigo-500 text-white px-6 py-3 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20">
                    <i class="fas fa-print text-sm"></i>
                    Imprimer
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Paiements -->
            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-[2rem] opacity-10 group-hover:opacity-20 blur transition duration-500">
                </div>
                <div class="relative glass p-8 rounded-[2rem] border border-white/5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Total Paiements
                    </h3>
                    <p class="text-3xl font-black text-white leading-none">{{ $data['total_payments'] }}</p>
                    <div class="mt-4 flex flex-col gap-1">
                        <span
                            class="text-[10px] font-black text-emerald-400 uppercase tracking-widest flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> {{ $data['paid_payments'] }} payés
                        </span>
                        <span
                            class="text-[10px] font-black text-amber-400 uppercase tracking-widest flex items-center gap-1">
                            <i class="fas fa-clock"></i> {{ $data['pending_payments'] }} en attente
                        </span>
                    </div>
                </div>
            </div>

            <!-- Relevés -->
            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-[2rem] opacity-10 group-hover:opacity-20 blur transition duration-500">
                </div>
                <div class="relative glass p-8 rounded-[2rem] border border-white/5">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400">
                            <i class="fas fa-file-invoice text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Relevés Bancaires
                    </h3>
                    <p class="text-3xl font-black text-white leading-none">{{ $data['total_statements'] }}</p>
                    <div class="mt-4 flex flex-col gap-1">
                        <span
                            class="text-[10px] font-black text-emerald-400 uppercase tracking-widest flex items-center gap-1">
                            <i class="fas fa-link"></i> {{ $data['matched_statements'] }} appariés
                        </span>
                        <span
                            class="text-[10px] font-black text-rose-400 uppercase tracking-widest flex items-center gap-1">
                            <i class="fas fa-exclamation-triangle"></i> {{ $data['exception_statements'] }} exceptions
                        </span>
                    </div>
                </div>
            </div>

            <!-- Taux de Succès -->
            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-[2rem] opacity-10 group-hover:opacity-20 blur transition duration-500">
                </div>
                <div class="relative glass p-8 rounded-[2rem] border border-white/5">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                            <i class="fas fa-bullseye text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Taux de Succès
                    </h3>
                    <p class="text-3xl font-black text-white leading-none">{{ number_format($data['match_rate'], 1) }}%
                    </p>
                    <p class="mt-4 text-[10px] text-slate-500 font-bold uppercase tracking-widest">Efficacité du
                        rapprochement</p>
                </div>
            </div>

            <!-- Action Rapide -->
            <div class="relative group h-full">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-rose-500 to-red-500 rounded-[2rem] opacity-20 blur transition duration-500">
                </div>
                <div
                    class="relative h-full bg-gradient-to-br from-slate-800 to-slate-900 p-8 rounded-[2rem] border border-white/10 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-black text-white mb-2">Exceptions</h3>
                        <p class="text-slate-400 text-xs font-medium leading-relaxed">
                            {{ $data['exception_statements'] }} transactions nécessitent une vérification manuelle.
                        </p>
                    </div>
                    <a href="{{ route('admin.reconciliation.exceptions') }}"
                        class="mt-6 w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-white text-slate-900 rounded-xl font-black text-xs hover:bg-slate-100 transition-all">
                        Gérer les exceptions
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Matches Table -->
        <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
            <div class="px-8 py-6 bg-white/5 border-b border-white/5 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black text-white">Derniers Rapprochements</h2>
                    <p class="text-xs text-slate-500 font-medium mt-1">Historique des 20 dernières opérations réussies
                    </p>
                </div>
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                    Temps réel
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-6">Date & Heure</th>
                            <th class="px-8 py-6">Utilisateur / Transaction</th>
                            <th class="px-8 py-6">Source Bancaire</th>
                            <th class="px-8 py-6 text-right">Montant</th>
                            <th class="px-8 py-6 text-center">Score</th>
                            <th class="px-8 py-6 text-center">Mode</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($data['recent_matches'] as $match)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-white">{{ $match->created_at->format('d/m/Y') }}</span>
                                        <span
                                            class="text-[10px] text-slate-500 font-bold">{{ $match->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 font-black shadow-lg group-hover:scale-110 transition-transform border border-indigo-500/20">
                                            {{ substr($match->payment->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-white">
                                                {{ $match->payment->user->name ?? 'Utilisateur Inconnu' }}
                                            </div>
                                            <div
                                                class="text-[10px] text-slate-500 font-mono font-bold bg-white/5 px-2 py-0.5 rounded-lg mt-1 inline-block">
                                                {{ $match->payment->transaction_id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            {{ $match->bankStatement->source ?? 'N/A' }}
                                        </span>
                                        <span class="text-[10px] text-slate-600 font-mono font-bold mt-1">
                                            {{ $match->bankStatement->reference }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="text-sm font-black text-white">
                                        {{ number_format($match->payment->amount, 0, ',', ' ') }} <span
                                            class="text-[10px] text-slate-500 ml-1">Ar</span>
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-center gap-2">
                                        @php
                                            $scoreColor = $match->score >= 80 ? 'emerald' : ($match->score >= 50 ? 'amber' : 'rose');
                                        @endphp
                                        <div class="w-20 bg-white/5 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-{{ $scoreColor }}-500 h-full rounded-full"
                                                style="width: {{ $match->score }}%"></div>
                                        </div>
                                        <span
                                            class="text-[10px] font-black text-{{ $scoreColor }}-400">{{ $match->score }}%</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center">
                                        @if($match->status === 'auto')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                <i class="fas fa-bolt"></i>
                                                Auto
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-purple-500/10 text-purple-400 border border-purple-500/20">
                                                <i class="fas fa-user-edit"></i>
                                                Manuel
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div
                                            class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center text-slate-700">
                                            <i class="fas fa-history text-4xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucun
                                            rapprochement trouvé</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>