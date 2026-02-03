<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                    <i class="fas fa-money-check-alt text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Gestion des Paiements') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Supervisez et validez les transactions
                        financières des étudiants.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Filters & Search -->
        <div class="glass p-6 rounded-[2rem] border border-white/5">
            <form action="{{ route('admin.payments.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative group/input">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-search text-slate-600 text-sm group-focus-within/input:text-indigo-400 transition-colors"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher un étudiant, un email ou une transaction..."
                        class="block w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium">
                </div>
                <div class="w-full md:w-64 relative group/select">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-filter text-slate-600 text-sm group-focus-within/select:text-indigo-400 transition-colors"></i>
                    </div>
                    <select name="status"
                        class="block w-full pl-12 pr-10 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                        <option value="" class="bg-[#1E293B]">Tous les statuts</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }} class="bg-[#1E293B]">✅
                            Payé</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}
                            class="bg-[#1E293B]">⏳ En attente</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}
                            class="bg-[#1E293B]">❌ Échoué</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-600">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
                <button type="submit"
                    class="bg-indigo-500 text-white px-8 py-3 rounded-xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20">
                    Filtrer
                </button>
            </form>
        </div>

        @if(session('success'))
            <div
                class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                <i class="fas fa-check-circle text-lg"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Payments Table -->
        <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full min-w-[1000px] text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-6">Étudiant</th>
                            <th class="px-8 py-6">Transaction</th>
                            <th class="px-8 py-6">Montant</th>
                            <th class="px-8 py-6 text-center">Statut</th>
                            <th class="px-8 py-6">Preuve</th>
                            <th class="px-8 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-white/[0.02] transition-colors group align-top">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500/20 to-purple-500/20 flex items-center justify-center text-indigo-400 font-black border border-indigo-500/20">
                                            {{ substr($payment->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-white">{{ $payment->user->name }}</span>
                                            <span
                                                class="text-[10px] font-bold text-slate-500">{{ $payment->user->email }}</span>
                                            <span
                                                class="text-[10px] font-black text-indigo-400 uppercase tracking-tighter mt-1">{{ $payment->type }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs font-mono font-bold text-slate-400 bg-white/5 px-2 py-1 rounded-lg w-fit">{{ $payment->transaction_id }}</span>
                                        <span
                                            class="text-[10px] text-slate-500 font-bold mt-2">{{ $payment->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-lg font-black text-white">{{ number_format($payment->amount, 0, ',', ' ') }}
                                            <span class="text-[10px] text-slate-500 ml-1">Ar</span></span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col items-center gap-2">
                                        <span
                                            class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                                    {{ $payment->status === 'paid' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($payment->status === 'pending' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                                            {{ $payment->status }}
                                        </span>
                                        @if($payment->metadata && isset($payment->metadata['internal_notes']))
                                            <span class="text-[10px] text-indigo-400 font-black uppercase tracking-tighter">
                                                <i class="fas fa-sticky-note mr-1"></i>
                                                {{ count($payment->metadata['internal_notes']) }} notes
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($payment->metadata && isset($payment->metadata['proof_path']))
                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($payment->metadata['proof_path']) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-2 px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400 hover:bg-white/10 hover:text-white transition-all">
                                            <i class="fas fa-eye text-indigo-400"></i>
                                            Voir preuve
                                        </a>
                                    @else
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-600">Aucune
                                            preuve</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex flex-col gap-2">
                                        @if($payment->status === 'pending' && $payment->metadata && isset($payment->metadata['proof_path']))
                                            <div x-data="{ open: false }">
                                                <button @click="open = true"
                                                    class="w-full bg-emerald-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20">Valider</button>
                                                <div x-show="open"
                                                    class="fixed inset-0 bg-[#0F172A]/90 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                                                    x-cloak>
                                                    <div
                                                        class="glass border border-white/10 p-8 rounded-[2.5rem] max-w-md w-full shadow-2xl">
                                                        <h3 class="text-xl font-black text-white mb-6">Valider le paiement</h3>
                                                        <form action="{{ route('admin.payments.validate-proof', $payment) }}"
                                                            method="POST" class="space-y-6">
                                                            @csrf
                                                            <div class="space-y-2">
                                                                <label
                                                                    class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Note
                                                                    Interne (Optionnelle)</label>
                                                                <textarea name="note" placeholder="Note pour l'équipe..."
                                                                    class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all font-medium h-32"></textarea>
                                                            </div>
                                                            <div class="flex justify-end gap-4">
                                                                <button type="button" @click="open = false"
                                                                    class="text-xs font-black text-slate-500 hover:text-white transition-colors uppercase tracking-widest">Annuler</button>
                                                                <button type="submit"
                                                                    class="bg-emerald-500 text-white px-6 py-3 rounded-xl font-black hover:bg-emerald-600 transition-all shadow-xl shadow-emerald-500/20">Confirmer</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div x-data="{ open: false }">
                                                <button @click="open = true"
                                                    class="w-full bg-rose-500 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 transition-all shadow-lg shadow-rose-500/20">Rejeter</button>
                                                <div x-show="open"
                                                    class="fixed inset-0 bg-[#0F172A]/90 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                                                    x-cloak>
                                                    <div
                                                        class="glass border border-white/10 p-8 rounded-[2.5rem] max-w-md w-full shadow-2xl">
                                                        <h3 class="text-xl font-black text-white mb-6">Rejeter le paiement</h3>
                                                        <form action="{{ route('admin.payments.reject-proof', $payment) }}"
                                                            method="POST" class="space-y-6">
                                                            @csrf
                                                            <div class="space-y-2">
                                                                <label
                                                                    class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Motif
                                                                    du rejet</label>
                                                                <textarea name="reason" required
                                                                    placeholder="Expliquez pourquoi le paiement est rejeté..."
                                                                    class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all font-medium h-32"></textarea>
                                                            </div>
                                                            <div class="flex justify-end gap-4">
                                                                <button type="button" @click="open = false"
                                                                    class="text-xs font-black text-slate-500 hover:text-white transition-colors uppercase tracking-widest">Annuler</button>
                                                                <button type="submit"
                                                                    class="bg-rose-500 text-white px-6 py-3 rounded-xl font-black hover:bg-rose-600 transition-all shadow-xl shadow-rose-500/20">Confirmer
                                                                    le rejet</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div x-data="{ open: false }">
                                            <button @click="open = true"
                                                class="w-full bg-white/5 border border-white/10 text-slate-400 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white/10 hover:text-white transition-all">Notes
                                                Internes</button>
                                            <div x-show="open"
                                                class="fixed inset-0 bg-[#0F172A]/90 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                                                x-cloak>
                                                <div
                                                    class="glass border border-white/10 p-8 rounded-[2.5rem] max-w-lg w-full shadow-2xl">
                                                    <h3 class="text-xl font-black text-white mb-6">Notes Internes</h3>

                                                    <div
                                                        class="max-h-64 overflow-y-auto mb-6 space-y-4 scrollbar-thin scrollbar-thumb-indigo-500/20 scrollbar-track-transparent pr-2">
                                                        @if($payment->metadata && isset($payment->metadata['internal_notes']))
                                                            @foreach($payment->metadata['internal_notes'] as $note)
                                                                <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                                                                    <div class="flex justify-between items-center mb-2">
                                                                        <span
                                                                            class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">{{ $note['user_name'] }}</span>
                                                                        <span
                                                                            class="text-[10px] font-bold text-slate-500">{{ \Carbon\Carbon::parse($note['created_at'])->format('d/m/Y H:i') }}</span>
                                                                    </div>
                                                                    <p class="text-xs text-slate-300 leading-relaxed">
                                                                        {{ $note['note'] }}
                                                                    </p>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <div class="text-center py-8">
                                                                <i class="fas fa-sticky-note text-slate-700 text-3xl mb-3"></i>
                                                                <p
                                                                    class="text-xs text-slate-500 font-bold uppercase tracking-widest">
                                                                    Aucune note pour le moment</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <form action="{{ route('admin.payments.internal-note', $payment) }}"
                                                        method="POST" class="space-y-6">
                                                        @csrf
                                                        <div class="space-y-2">
                                                            <label
                                                                class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Ajouter
                                                                une note</label>
                                                            <textarea name="note" required placeholder="Votre note ici..."
                                                                class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium h-24"></textarea>
                                                        </div>
                                                        <div class="flex justify-end gap-4">
                                                            <button type="button" @click="open = false"
                                                                class="text-xs font-black text-slate-500 hover:text-white transition-colors uppercase tracking-widest">Fermer</button>
                                                            <button type="submit"
                                                                class="bg-indigo-500 text-white px-6 py-3 rounded-xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20">Ajouter</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        @if($payment->status === 'paid')
                                            <a href="{{ route('payments.receipt', $payment) }}"
                                                class="w-full inline-flex items-center justify-center gap-2 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-500/20 transition-all">
                                                <i class="fas fa-file-invoice"></i>
                                                Reçu
                                            </a>
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
                                            <i class="fas fa-money-bill-wave text-4xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucun paiement
                                            trouvé</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payments->hasPages())
                <div class="px-8 py-6 bg-white/5 border-t border-white/5">
                    {{ $payments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>