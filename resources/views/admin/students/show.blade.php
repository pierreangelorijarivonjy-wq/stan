<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Détails de l'étudiant et historique complet.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.students.index') }}"
                    class="inline-flex items-center gap-2 bg-white/5 border border-white/10 text-white px-6 py-3 rounded-2xl font-black hover:bg-white/10 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Retour à la liste
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Profile Header Card -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-[2.5rem] opacity-10 blur transition duration-500"></div>
            <div class="relative glass p-8 sm:p-10 rounded-[2.5rem] border border-white/5">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                    <div class="w-32 h-32 rounded-[2rem] bg-gradient-to-br from-blue-500/20 to-indigo-500/20 flex items-center justify-center text-blue-400 text-4xl font-black shadow-2xl border border-blue-500/20">
                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <div class="flex flex-wrap justify-center md:justify-start gap-3 mb-4">
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $student->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                {{ $student->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-white/5 text-slate-400 border border-white/10">
                                Inscrit le {{ $student->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                        <h1 class="text-4xl font-black text-white mb-2">{{ $student->first_name }} {{ $student->last_name }}</h1>
                        <p class="text-slate-500 font-mono font-bold text-lg">MATRICULE: <span class="text-indigo-400">{{ $student->matricule }}</span></p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-8">
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Email</p>
                                <p class="text-sm font-bold text-white truncate">{{ $student->user->email }}</p>
                            </div>
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Téléphone</p>
                                <p class="text-sm font-bold text-white">{{ $student->phone ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Pièce d'identité</p>
                                <p class="text-sm font-bold text-white">{{ $student->piece_id ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Stats -->
            <div class="lg:col-span-1 space-y-6">
                <h3 class="text-lg font-black text-white flex items-center gap-3 ml-2">
                    <i class="fas fa-chart-pie text-indigo-400"></i>
                    Statistiques
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <div class="glass p-6 rounded-[2rem] border border-white/5 flex items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                            <i class="fas fa-credit-card text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Paiements</p>
                            <p class="text-2xl font-black text-white">{{ $student->payments->count() }}</p>
                        </div>
                    </div>
                    <div class="glass p-6 rounded-[2rem] border border-white/5 flex items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-purple-500/10 flex items-center justify-center text-purple-400">
                            <i class="fas fa-file-alt text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Convocations</p>
                            <p class="text-2xl font-black text-white">{{ $student->convocations->count() }}</p>
                        </div>
                    </div>
                    <div class="glass p-6 rounded-[2rem] border border-white/5 flex items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-400">
                            <i class="fas fa-book text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Cours inscrits</p>
                            <p class="text-2xl font-black text-white">{{ $student->courses->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des Paiements -->
            <div class="lg:col-span-2 space-y-6">
                <h3 class="text-lg font-black text-white flex items-center gap-3 ml-2">
                    <i class="fas fa-history text-emerald-400"></i>
                    Historique des Paiements
                </h3>
                <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                                    <th class="px-8 py-6">Référence</th>
                                    <th class="px-8 py-6">Montant</th>
                                    <th class="px-8 py-6">Date</th>
                                    <th class="px-8 py-6 text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($student->payments as $payment)
                                    <tr class="hover:bg-white/[0.02] transition-colors">
                                        <td class="px-8 py-6">
                                            <span class="text-xs font-mono font-bold text-slate-500 bg-white/5 px-2 py-1 rounded-lg">
                                                {{ $payment->reference }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="text-sm font-black text-white">{{ number_format($payment->amount, 0, ',', ' ') }} <span class="text-[10px] text-slate-500 ml-1">Ar</span></span>
                                        </td>
                                        <td class="px-8 py-6 text-xs text-slate-500 font-medium">
                                            {{ $payment->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex justify-center">
                                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $payment->status === 'paid' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                                    {{ $payment->status }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-8 py-12 text-center text-slate-600 font-bold uppercase tracking-widest text-xs italic">
                                            Aucun paiement enregistré
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Convocations -->
        <div class="space-y-6">
            <h3 class="text-lg font-black text-white flex items-center gap-3 ml-2">
                <i class="fas fa-id-card text-purple-400"></i>
                Convocations aux Examens
            </h3>
            <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                                <th class="px-8 py-6">Session d'Examen</th>
                                <th class="px-8 py-6">Date</th>
                                <th class="px-8 py-6">Centre</th>
                                <th class="px-8 py-6 text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($student->convocations as $convocation)
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-white">{{ $convocation->examSession->title }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-sm font-bold text-slate-400">
                                        {{ $convocation->examSession->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-8 py-6 text-sm font-bold text-slate-400">
                                        {{ $convocation->examSession->center }}
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex justify-center">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $convocation->status === 'sent' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-white/5 text-slate-500 border border-white/10' }}">
                                                {{ $convocation->status }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-slate-600 font-bold uppercase tracking-widest text-xs italic">
                                        Aucune convocation générée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>