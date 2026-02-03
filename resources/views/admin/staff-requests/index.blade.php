<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                    <i class="fas fa-user-shield text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Demandes d\'inscription Staff') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Approuvez ou rejetez les demandes d'accès pour le
                        personnel.</p>
                </div>
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
                <i class="fas fa-exclamation-triangle text-lg"></i>
                <p class="font-bold">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Staff Requests Table -->
        <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-6">Candidat</th>
                            <th class="px-8 py-6">Rôle demandé</th>
                            <th class="px-8 py-6">Date de demande</th>
                            <th class="px-8 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($requests as $request)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/20 to-indigo-500/20 flex items-center justify-center text-blue-400 font-black border border-blue-500/20">
                                            {{ substr($request->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-black text-white group-hover:text-blue-400 transition-colors">{{ $request->name }}</span>
                                            <span class="text-[10px] font-bold text-slate-500">{{ $request->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                        {{ $request->requested_role }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2 text-xs font-bold text-slate-400">
                                        <i class="fas fa-clock text-indigo-400 text-[10px]"></i>
                                        {{ $request->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        <form action="{{ route('admin.staff-requests.approve', $request) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-500/20 transition-all">
                                                <i class="fas fa-check"></i>
                                                Approuver
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.staff-requests.reject', $request) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 bg-rose-500/10 border border-rose-500/20 text-rose-400 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-500/20 transition-all">
                                                <i class="fas fa-times"></i>
                                                Rejeter
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div
                                            class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center text-slate-700">
                                            <i class="fas fa-user-clock text-4xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucune demande
                                            en attente</p>
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