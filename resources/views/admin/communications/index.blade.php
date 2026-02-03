<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Communications Officielles') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Gérez les annonces et les notifications envoyées
                        aux étudiants.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.communications.create') }}"
                    class="inline-flex items-center gap-2 bg-indigo-500 text-white px-6 py-3 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20">
                    <i class="fas fa-plus text-sm"></i>
                    Nouvelle Communication
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Communications Table -->
        <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full min-w-[900px] text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-6">Titre / Type</th>
                            <th class="px-8 py-6">Canaux</th>
                            <th class="px-8 py-6">Cible</th>
                            <th class="px-8 py-6">Programmation</th>
                            <th class="px-8 py-6 text-center">Statut</th>
                            <th class="px-8 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($communications as $comm)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-white group-hover:text-indigo-400 transition-colors">{{ $comm->title }}</span>
                                        <span
                                            class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">{{ $comm->type }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($comm->channels as $channel)
                                            <span
                                                class="px-2 py-1 bg-white/5 border border-white/10 rounded-lg text-[10px] font-black uppercase tracking-widest text-slate-400">
                                                <i
                                                    class="fas fa-{{ $channel === 'email' ? 'envelope' : ($channel === 'sms' ? 'comment-alt' : 'bell') }} mr-1 text-indigo-400"></i>
                                                {{ $channel }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="text-xs font-bold text-slate-400">{{ $comm->target }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2 text-xs font-bold text-white">
                                        <i class="fas fa-clock text-indigo-400 text-[10px]"></i>
                                        @if($comm->scheduled_at)
                                            {{ $comm->scheduled_at->format('d/m/Y H:i') }}
                                        @else
                                            <span
                                                class="text-emerald-400 uppercase tracking-widest text-[10px] font-black">Immédiat</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                                    {{ $comm->status === 'sent' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}
                                                ">
                                            {{ $comm->status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        @if($comm->status === 'draft')
                                            <form action="{{ route('admin.communications.send', $comm) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all border border-white/10"
                                                    title="Envoyer maintenant">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.communications.show', $comm) }}"
                                            class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 transition-all border border-white/10">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div
                                            class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center text-slate-700">
                                            <i class="fas fa-bullhorn text-4xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucune
                                            communication enregistrée</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($communications->hasPages())
                <div class="px-8 py-6 bg-white/5 border-t border-white/5">
                    {{ $communications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>