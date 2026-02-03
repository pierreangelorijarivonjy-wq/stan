<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 flex items-center justify-center text-white shadow-lg shadow-rose-500/20">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Exceptions de Rapprochement') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Gérez manuellement les transactions sans
                        correspondance automatique.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.reconciliation.index') }}"
                    class="inline-flex items-center gap-2 bg-white/5 border border-white/10 text-white px-6 py-3 rounded-2xl font-black hover:bg-white/10 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Liste des Exceptions -->
            <div class="lg:col-span-2 space-y-6">
                <h3 class="text-lg font-black text-white flex items-center gap-3 ml-2">
                    <i class="fas fa-list text-rose-400"></i>
                    Relevés Bancaires sans Match
                </h3>

                <div class="space-y-4">
                    @forelse($exceptionStatements as $statement)
                        <div class="relative group">
                            <div
                                class="absolute -inset-0.5 bg-gradient-to-r from-slate-700 to-slate-800 rounded-[2rem] opacity-10 group-hover:opacity-20 blur transition duration-500">
                            </div>
                            <div
                                class="relative glass p-6 rounded-[2rem] border border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-slate-400 font-black border border-white/10">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-500">{{ $statement->source }}</span>
                                            <span class="text-[10px] font-bold text-slate-600">•</span>
                                            <span
                                                class="text-[10px] font-bold text-slate-600">{{ $statement->date->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="text-xl font-black text-white">
                                            {{ number_format($statement->amount, 0, ',', ' ') }} <span
                                                class="text-xs text-slate-500 ml-1">Ar</span>
                                        </div>
                                        <div class="text-[10px] text-slate-500 font-mono font-bold mt-1">REF:
                                            {{ $statement->reference }}</div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                                    <div x-data="{ open: false }" class="flex-1 md:flex-none">
                                        <button @click="open = true"
                                            class="w-full bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-xs font-black hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20 flex items-center justify-center gap-2">
                                            <i class="fas fa-link"></i> Match Manuel
                                        </button>

                                        <!-- Modal Match Manuel -->
                                        <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            class="fixed inset-0 bg-[#0F172A]/90 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                                            x-cloak>
                                            <div @click.away="open = false"
                                                class="glass border border-white/10 p-8 rounded-[2.5rem] max-w-2xl w-full shadow-2xl">
                                                <div class="flex justify-between items-center mb-8">
                                                    <h3 class="text-2xl font-black text-white">Appariement Manuel</h3>
                                                    <button @click="open = false"
                                                        class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:text-white transition-colors">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>

                                                <div
                                                    class="bg-indigo-500/10 border border-indigo-500/20 rounded-2xl p-4 mb-8">
                                                    <p class="text-xs text-indigo-300 font-medium leading-relaxed">
                                                        Associer ce relevé de <span
                                                            class="font-black text-white">{{ number_format($statement->amount, 0, ',', ' ') }}
                                                            Ar</span> à un paiement en attente.
                                                    </p>
                                                </div>

                                                <form action="{{ route('admin.reconciliation.manual-match') }}"
                                                    method="POST" class="space-y-6">
                                                    @csrf
                                                    <input type="hidden" name="bank_statement_id"
                                                        value="{{ $statement->id }}">

                                                    <div class="space-y-2">
                                                        <label
                                                            class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Choisir
                                                            le paiement</label>
                                                        <div class="relative group/select">
                                                            <div
                                                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                                <i
                                                                    class="fas fa-search text-slate-600 text-sm group-focus-within/select:text-indigo-400 transition-colors"></i>
                                                            </div>
                                                            <select name="payment_id" required
                                                                class="block w-full pl-12 pr-10 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                                                                <option value="" class="bg-[#1E293B]">-- Sélectionner un
                                                                    paiement --</option>
                                                                @foreach($pendingPayments as $payment)
                                                                    <option value="{{ $payment->id }}" class="bg-[#1E293B]">
                                                                        {{ $payment->user->name }} -
                                                                        {{ number_format($payment->amount, 0, ',', ' ') }} Ar
                                                                        ({{ $payment->transaction_id }})
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div
                                                                class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-600">
                                                                <i class="fas fa-chevron-down text-xs"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="space-y-2">
                                                        <label
                                                            class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Motif
                                                            de l'appariement</label>
                                                        <textarea name="reason" required minlength="10"
                                                            placeholder="Expliquez pourquoi vous associez ces deux transactions..."
                                                            class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium h-32"></textarea>
                                                    </div>

                                                    <div class="flex justify-end gap-4 pt-4">
                                                        <button type="button" @click="open = false"
                                                            class="px-8 py-4 text-sm font-black text-slate-400 hover:text-white transition-colors">Annuler</button>
                                                        <button type="submit"
                                                            class="bg-indigo-500 text-white px-10 py-4 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20">
                                                            Valider l'appariement
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-data="{ open: false }" class="flex-1 md:flex-none">
                                        <button @click="open = true"
                                            class="w-full bg-white/5 border border-white/10 text-slate-400 px-5 py-2.5 rounded-xl text-xs font-black hover:text-white hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                                            <i class="fas fa-paper-plane"></i> Justificatif
                                        </button>

                                        <!-- Modal Justificatif -->
                                        <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            class="fixed inset-0 bg-[#0F172A]/90 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                                            x-cloak>
                                            <div @click.away="open = false"
                                                class="glass border border-white/10 p-8 rounded-[2.5rem] max-w-md w-full shadow-2xl">
                                                <div class="flex justify-between items-center mb-8">
                                                    <h3 class="text-2xl font-black text-white">Justificatif</h3>
                                                    <button @click="open = false"
                                                        class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:text-white transition-colors">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>

                                                <form
                                                    action="{{ route('admin.reconciliation.request-justification', $statement) }}"
                                                    method="POST" class="space-y-6">
                                                    @csrf
                                                    <div class="space-y-2">
                                                        <label
                                                            class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Message
                                                            pour l'étudiant</label>
                                                        <textarea name="message" required
                                                            placeholder="Expliquez quel document est nécessaire..."
                                                            class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium h-40"></textarea>
                                                    </div>

                                                    <div class="flex justify-end gap-4 pt-4">
                                                        <button type="button" @click="open = false"
                                                            class="px-8 py-4 text-sm font-black text-slate-400 hover:text-white transition-colors">Annuler</button>
                                                        <button type="submit"
                                                            class="bg-indigo-500 text-white px-10 py-4 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20">
                                                            Envoyer la demande
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($statement->raw_data && isset($statement->raw_data['justification_requested']))
                                <div
                                    class="mt-3 mx-8 p-4 bg-amber-500/10 border border-amber-500/20 rounded-2xl text-[10px] text-amber-400 flex items-start gap-3">
                                    <i class="fas fa-info-circle text-sm mt-0.5"></i>
                                    <div>
                                        <span class="font-black uppercase tracking-widest">Justificatif demandé le
                                            {{ \Carbon\Carbon::parse($statement->raw_data['requested_at'])->format('d/m/Y') }}
                                            :</span>
                                        <p class="mt-1 font-medium text-slate-400 italic">
                                            "{{ $statement->raw_data['justification_message'] }}"</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="glass p-20 rounded-[2.5rem] border border-white/5 text-center">
                            <div
                                class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center text-slate-700 mx-auto mb-6">
                                <i class="fas fa-check-double text-4xl text-emerald-500/20"></i>
                            </div>
                            <p class="text-slate-500 font-black uppercase tracking-widest text-sm">Aucune exception à
                                traiter</p>
                            <p class="text-slate-600 text-xs mt-2">Toutes les transactions sont parfaitement appariées.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $exceptionStatements->links() }}
                </div>
            </div>

            <!-- Guide de Traitement -->
            <div class="space-y-6">
                <h3 class="text-lg font-black text-white flex items-center gap-3 ml-2">
                    <i class="fas fa-book-open text-indigo-400"></i>
                    Guide de Traitement
                </h3>

                <div class="glass p-8 rounded-[2.5rem] border border-white/5 space-y-8">
                    <div class="flex gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 font-black text-sm border border-indigo-500/20 shrink-0">
                            1</div>
                        <div>
                            <p class="text-sm font-black text-white mb-1">Vérifiez les montants</p>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">Le rapprochement manuel est
                                souvent nécessaire quand un étudiant paie en plusieurs fois ou quand la référence est
                                erronée.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 font-black text-sm border border-indigo-500/20 shrink-0">
                            2</div>
                        <div>
                            <p class="text-sm font-black text-white mb-1">Motif obligatoire</p>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">Toute action manuelle doit
                                être justifiée pour l'audit financier. Soyez précis dans vos notes de rapprochement.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 font-black text-sm border border-indigo-500/20 shrink-0">
                            3</div>
                        <div>
                            <p class="text-sm font-black text-white mb-1">Justificatifs</p>
                            <p class="text-xs text-slate-500 font-medium leading-relaxed">Si vous ne trouvez aucun
                                paiement correspondant, marquez le relevé pour demander un justificatif à l'étudiant.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Stats rapides -->
                <div
                    class="bg-gradient-to-br from-indigo-500/10 to-purple-600/10 p-8 rounded-[2.5rem] border border-indigo-500/20">
                    <div class="flex items-center gap-4 mb-4">
                        <i class="fas fa-info-circle text-indigo-400"></i>
                        <span class="text-xs font-black text-white uppercase tracking-widest">Info Audit</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">
                        Toutes les actions effectuées sur cette page sont enregistrées dans les logs d'audit avec votre
                        identifiant et l'horodatage précis.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>