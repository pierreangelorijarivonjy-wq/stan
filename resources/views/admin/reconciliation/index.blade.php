<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                    <i class="fas fa-sync-alt text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Rapprochement Bancaire') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Importez vos relevés et lancez le rapprochement
                        automatique.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <form action="{{ route('admin.reconciliation.match') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-3 rounded-2xl font-black hover:shadow-xl hover:shadow-indigo-500/25 transition-all hover:-translate-y-1">
                        <i class="fas fa-magic text-sm"></i>
                        Rapprochement 1-Clic
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        @if(session('success'))
            <div
                class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                <i class="fas fa-check-circle text-lg"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div
                class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                <i class="fas fa-exclamation-circle text-lg"></i>
                <p class="font-bold">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Statistiques -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-[2rem] opacity-10 group-hover:opacity-20 blur transition duration-500">
                </div>
                <div class="relative glass p-8 rounded-[2rem] border border-white/5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Paiements en attente
                    </h3>
                    <p class="text-3xl font-black text-white leading-none">{{ $pendingPayments }}</p>
                </div>
            </div>

            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-[2rem] opacity-10 group-hover:opacity-20 blur transition duration-500">
                </div>
                <div class="relative glass p-8 rounded-[2rem] border border-white/5">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400">
                            <i class="fas fa-unlink text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Relevés non appariés
                    </h3>
                    <p class="text-3xl font-black text-white leading-none">{{ $unmatchedStatements }}</p>
                </div>
            </div>

            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-[2rem] opacity-10 group-hover:opacity-20 blur transition duration-500">
                </div>
                <div class="relative glass p-8 rounded-[2rem] border border-white/5">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                            <i class="fas fa-percentage text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Taux d'appariement</h3>
                    <p class="text-3xl font-black text-white leading-none">{{ number_format($matchRate, 1) }}%</p>
                </div>
            </div>
        </div>

        <!-- Import CSV -->
        <div class="relative group">
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-[2.5rem] opacity-10 blur transition duration-500">
            </div>
            <div class="relative glass p-8 sm:p-10 rounded-[2.5rem] border border-white/5">
                <h2 class="text-xl font-black text-white mb-8 flex items-center gap-3">
                    <i class="fas fa-file-import text-indigo-400"></i>
                    Importer un relevé bancaire
                </h2>
                <form action="{{ route('admin.reconciliation.import') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Source
                                du relevé</label>
                            <div class="relative group/select">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-university text-slate-600 text-sm group-focus-within/select:text-indigo-400 transition-colors"></i>
                                </div>
                                <select name="source"
                                    class="block w-full pl-12 pr-10 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer"
                                    required>
                                    <option value="mvola" class="bg-[#1E293B]">MVola</option>
                                    <option value="orange" class="bg-[#1E293B]">Orange Money</option>
                                    <option value="airtel" class="bg-[#1E293B]">Airtel Money</option>
                                    <option value="bni" class="bg-[#1E293B]">BNI Madagascar</option>
                                    <option value="bfv" class="bg-[#1E293B]">BFV-SG</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-600">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Fichier
                                CSV / TXT</label>
                            <div class="relative group/file">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-file-csv text-slate-600 text-sm group-focus-within/file:text-indigo-400 transition-colors"></i>
                                </div>
                                <input type="file" name="csv_file" accept=".csv,.txt"
                                    class="block w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-500/10 file:text-indigo-400 hover:file:bg-indigo-500/20 transition-all cursor-pointer"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="bg-indigo-500 text-white px-10 py-4 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20 flex items-center gap-3">
                            <i class="fas fa-upload"></i>
                            Lancer l'importation
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des relevés -->
        <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
            <div class="px-8 py-6 bg-white/5 border-b border-white/5 flex justify-between items-center">
                <h2 class="text-xl font-black text-white">Relevés bancaires récents</h2>
                <div class="flex gap-4">
                    <a href="{{ route('admin.reconciliation.report') }}"
                        class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs font-black text-slate-400 hover:text-white hover:bg-white/10 transition-all flex items-center gap-2">
                        <i class="fas fa-chart-bar text-indigo-400"></i>
                        Rapport complet
                    </a>
                    <a href="{{ route('admin.reconciliation.exceptions') }}"
                        class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs font-black text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-rose-400"></i>
                        Exceptions
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-white/10 scrollbar-track-transparent">
                <table class="min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-6">Date</th>
                            <th class="px-8 py-6">Source</th>
                            <th class="px-8 py-6">Référence</th>
                            <th class="px-8 py-6 text-right">Montant</th>
                            <th class="px-8 py-6 text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($statements as $statement)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6 text-sm font-bold text-white whitespace-nowrap">
                                    {{ $statement->date->format('d/m/Y') }}
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                        {{ strtoupper($statement->source) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="text-xs font-mono font-bold text-slate-500 bg-white/5 px-2 py-1 rounded-lg break-all">
                                        {{ $statement->reference }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right whitespace-nowrap">
                                    <span
                                        class="text-sm font-black text-white">{{ number_format($statement->amount, 0, ',', ' ') }}
                                        <span class="text-[10px] text-slate-500 ml-1">Ar</span></span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center whitespace-nowrap">
                                        @if($statement->status === 'matched')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/20">
                                                <i class="fas fa-check-circle"></i>
                                                Apparié
                                            </span>
                                        @elseif($statement->status === 'pending')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amber-500/20 text-amber-400 border border-amber-500/20">
                                                <i class="fas fa-clock"></i>
                                                En attente
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-rose-500/20 text-rose-400 border border-rose-500/20">
                                                <i class="fas fa-exclamation-circle"></i>
                                                Exception
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-6">
                                        <div
                                            class="w-24 h-24 bg-white/5 rounded-[2.5rem] flex items-center justify-center text-slate-700 shadow-inner">
                                            <i class="fas fa-university text-5xl opacity-20"></i>
                                        </div>
                                        <div>
                                            <p class="text-white font-black uppercase tracking-widest text-sm">Aucun relevé
                                                importé</p>
                                            <p class="text-slate-500 text-xs mt-2">Importez un fichier CSV pour commencer le
                                                rapprochement.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($statements->hasPages())
                <div class="px-8 py-6 bg-white/5 border-t border-white/5">
                    {{ $statements->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>