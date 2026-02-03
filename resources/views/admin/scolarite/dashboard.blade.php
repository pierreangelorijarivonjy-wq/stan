<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                    <i class="fas fa-university text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Tableau de Bord Scolarité') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Vue d'ensemble et gestion académique.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-10">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-[2rem] opacity-10 blur transition duration-500">
                </div>
                <div class="relative glass p-6 rounded-[2rem] border border-white/5">
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Sessions
                            Actives</span>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $data['active_sessions'] }} <span
                            class="text-sm text-slate-600">/ {{ $data['total_sessions'] }}</span></div>
                </div>
            </div>
            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-[2rem] opacity-10 blur transition duration-500">
                </div>
                <div class="relative glass p-6 rounded-[2rem] border border-white/5">
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Étudiants
                            Éligibles</span>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $data['eligible_students'] }} <span
                            class="text-sm text-slate-600">/ {{ $data['total_students'] }}</span></div>
                </div>
            </div>
            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-[2rem] opacity-10 blur transition duration-500">
                </div>
                <div class="relative glass p-6 rounded-[2rem] border border-white/5">
                    <div class="flex items-center gap-4 mb-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <span
                            class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Convocations</span>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $data['convocations_sent'] }} <span
                            class="text-sm text-slate-600">/ {{ $data['convocations_generated'] }}</span></div>
                </div>
            </div>
            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-rose-500 to-pink-500 rounded-[2rem] opacity-10 blur transition duration-500">
                </div>
                <div class="relative glass p-6 rounded-[2rem] border border-white/5">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center text-rose-400">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Taux de
                            Scan</span>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $data['scan_rate'] }}%</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Recent Sessions -->
            <div class="space-y-6">
                <h3 class="text-lg font-black text-white flex items-center gap-3 ml-2">
                    <i class="fas fa-history text-indigo-400"></i>
                    Sessions Récentes
                </h3>
                <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                                <th class="px-8 py-6">Type</th>
                                <th class="px-8 py-6">Date</th>
                                <th class="px-8 py-6 text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($data['recent_sessions'] as $session)
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-8 py-6 font-bold text-white text-sm">{{ $session->type }}</td>
                                    <td class="px-8 py-6 text-xs text-slate-400 font-bold">
                                        {{ $session->date->format('d/m/Y') }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex justify-center">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                                    {{ $session->status === 'planned' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                                                    {{ $session->status === 'ongoing' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : '' }}
                                                    {{ $session->status === 'completed' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : '' }}
                                                    {{ $session->status === 'cancelled' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : '' }}
                                                ">
                                                {{ $session->status }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Convocations -->
            <div class="space-y-6">
                <h3 class="text-lg font-black text-white flex items-center gap-3 ml-2">
                    <i class="fas fa-id-card text-emerald-400"></i>
                    Dernières Convocations
                </h3>
                <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                                <th class="px-8 py-6">Étudiant</th>
                                <th class="px-8 py-6">Session</th>
                                <th class="px-8 py-6 text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($data['recent_convocations'] as $convocation)
                                <tr class="hover:bg-white/[0.02] transition-colors">
                                    <td class="px-8 py-6 font-bold text-white text-sm">{{ $convocation->student->name }}
                                    </td>
                                    <td class="px-8 py-6 text-xs text-slate-400 font-bold">
                                        {{ $convocation->examSession->type }}</td>
                                    <td class="px-8 py-6">
                                        <div class="flex justify-center items-center gap-3">
                                            @if($convocation->sent_at)
                                                <i class="fas fa-paper-plane text-blue-400 text-[10px]" title="Envoyée"></i>
                                            @endif
                                            @if($convocation->downloaded_at)
                                                <i class="fas fa-download text-emerald-400 text-[10px]" title="Téléchargée"></i>
                                            @endif
                                            @if($convocation->scanned_at)
                                                <i class="fas fa-qrcode text-amber-400 text-[10px]" title="Scannée"></i>
                                            @endif
                                            <span
                                                class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $convocation->status }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="{{ route('admin.sessions.create') }}" class="relative group overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-blue-600 opacity-80 group-hover:scale-110 transition duration-500">
                </div>
                <div class="relative p-8 rounded-[2.5rem] border border-white/10 flex flex-col h-full">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-white mb-6">
                        <i class="fas fa-calendar-plus text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-white mb-2">Nouvelle Session</h4>
                    <p class="text-indigo-100 text-xs font-medium leading-relaxed">Planifiez un examen ou un
                        regroupement académique.</p>
                    <div
                        class="mt-8 flex items-center gap-2 text-white font-black text-[10px] uppercase tracking-widest">
                        Accéder <i
                            class="fas fa-arrow-right text-[8px] group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.convocations.create') }}" class="relative group overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-600 opacity-80 group-hover:scale-110 transition duration-500">
                </div>
                <div class="relative p-8 rounded-[2.5rem] border border-white/10 flex flex-col h-full">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-white mb-6">
                        <i class="fas fa-file-invoice text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-white mb-2">Générer Convocations</h4>
                    <p class="text-emerald-100 text-xs font-medium leading-relaxed">Génération individuelle ou en masse
                        pour les étudiants.</p>
                    <div
                        class="mt-8 flex items-center gap-2 text-white font-black text-[10px] uppercase tracking-widest">
                        Accéder <i
                            class="fas fa-arrow-right text-[8px] group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>
            <a href="{{ route('admin.results.index') }}" class="relative group overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-amber-500 to-orange-600 opacity-80 group-hover:scale-110 transition duration-500">
                </div>
                <div class="relative p-8 rounded-[2.5rem] border border-white/10 flex flex-col h-full">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-white mb-6">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-white mb-2">Saisir Résultats</h4>
                    <p class="text-amber-100 text-xs font-medium leading-relaxed">Importez ou saisissez manuellement les
                        notes.</p>
                    <div
                        class="mt-8 flex items-center gap-2 text-white font-black text-[10px] uppercase tracking-widest">
                        Accéder <i
                            class="fas fa-arrow-right text-[8px] group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>