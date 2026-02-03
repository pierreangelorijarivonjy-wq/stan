<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center text-white shadow-lg shadow-slate-900/20">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Journaux d\'Audit') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Tracez toutes les activités sensibles sur la
                        plateforme.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Audit Logs Table -->
        <div class="glass rounded-[2.5rem] border border-white/5 shadow-2xl">
            <div class="px-8 py-6 bg-white/5 border-b border-white/5">
                <form action="{{ route('admin.audit-logs.index') }}" method="GET"
                    class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i
                                class="fas fa-search text-slate-600 text-sm group-focus-within/input:text-indigo-400 transition-colors"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher par description, événement, IP ou utilisateur..."
                            class="block w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium">
                    </div>
                    <div class="w-full md:w-64 relative group/select">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i
                                class="fas fa-filter text-slate-600 text-sm group-focus-within/select:text-indigo-400 transition-colors"></i>
                        </div>
                        <select name="event" onchange="this.form.submit()"
                            class="block w-full pl-12 pr-10 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                            <option value="" class="bg-[#1E293B]">Tous les événements</option>
                            @foreach($events as $event)
                                <option value="{{ $event }}" {{ request('event') === $event ? 'selected' : '' }}
                                    class="bg-[#1E293B]">{{ $event }}</option>
                            @endforeach
                        </select>
                        <div
                            class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-600">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                    <button type="submit"
                        class="bg-indigo-500 text-white px-8 py-3 rounded-xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20">
                        Filtrer
                    </button>
                </form>
            </div>
            <div class="overflow-x-auto pb-4 custom-scrollbar">
                <table class="min-w-full min-w-[1000px] text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-6">Date & Heure</th>
                            <th class="px-8 py-6">Utilisateur</th>
                            <th class="px-8 py-6">Événement</th>
                            <th class="px-8 py-6">Description</th>
                            <th class="px-8 py-6">IP</th>
                            <th class="px-8 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($logs as $log)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-white">{{ $log->created_at->format('d/m/Y') }}</span>
                                        <span
                                            class="text-[10px] text-slate-500 font-bold">{{ $log->created_at->format('H:i:s') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400 font-black text-xs border border-white/10">
                                            {{ substr($log->user ? $log->user->name : 'S', 0, 1) }}
                                        </div>
                                        <span
                                            class="text-sm font-bold text-white">{{ $log->user ? $log->user->name : 'Système' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                        {{ $log->event }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-xs text-slate-400 font-medium max-w-xs truncate">
                                        {{ $log->readable_description }}
                                    </p>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="text-[10px] font-mono font-bold text-slate-600 bg-white/5 px-2 py-1 rounded-lg">
                                        {{ $log->ip_address }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('admin.audit-logs.show', $log) }}"
                                        class="inline-flex items-center gap-2 text-xs font-black text-indigo-400 hover:text-indigo-300 transition-colors">
                                        Détails
                                        <i class="fas fa-chevron-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="px-8 py-6 bg-white/5 border-t border-white/5">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>