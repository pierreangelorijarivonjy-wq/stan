<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Sessions d\'Examen') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Gérez les sessions, les centres et les horaires
                        d'examens.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <button @click="showCreateModal = true"
                    class="inline-flex items-center gap-2 bg-indigo-500 text-white px-6 py-3 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20">
                    <i class="fas fa-plus text-sm"></i>
                    Nouvelle Session
                </button>
            </div>
        </div>
    </x-slot>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="space-y-8" x-data="{ 
        showCreateModal: false,
        showConfirmModal: false,
        confirmTitle: '',
        confirmMessage: '',
        confirmFormId: null,
        confirmType: 'info',
        triggerConfirm(title, message, type, formId) {
            this.confirmTitle = title;
            this.confirmMessage = message;
            this.confirmType = type;
            this.confirmFormId = formId;
            this.showConfirmModal = true;
        },
        executeConfirm() {
            if (this.confirmFormId) {
                const form = document.getElementById(this.confirmFormId);
                if (form) form.submit();
            }
            this.showConfirmModal = false;
        }
    }">
        @if(session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                <i class="fas fa-check-circle text-lg"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                <i class="fas fa-exclamation-circle text-lg"></i>
                <p class="font-bold">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Sessions Table -->
        <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full min-w-[900px] text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-6">Type / Titre</th>
                            <th class="px-8 py-6">Centre / Salle</th>
                            <th class="px-8 py-6">Date & Heure</th>
                            <th class="px-8 py-6 text-center">Statut</th>
                            <th class="px-8 py-6 text-right min-w-[150px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($sessions as $session)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-indigo-400">{{ $session->type }}</span>
                                        <span class="text-xs font-bold text-white mt-1">{{ $session->title }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-white">{{ $session->center }}</span>
                                        <span
                                            class="text-[10px] text-slate-500 font-black uppercase tracking-widest mt-1">Salle:
                                            {{ $session->room }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-white">{{ $session->date->format('d/m/Y') }}</span>
                                        <span class="text-[10px] text-slate-500 font-bold mt-1">{{ $session->time }}</span>
                                    </div>
                                </td>
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
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-3 flex-nowrap">
                                        <button @click="/* Logic to open edit modal */"
                                            class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 transition-all border border-white/10">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if($session->status !== 'cancelled')
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div
                                            class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center text-slate-700">
                                            <i class="fas fa-calendar-times text-4xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucune session
                                            planifiée</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sessions->hasPages())
                <div class="px-8 py-6 bg-white/5 border-t border-white/5">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>

        <!-- Modals (Teleported to Body) -->
        <template x-teleport="body">
            <div class="relative z-[9999]">
                <!-- Create Modal -->
                <div x-show="showCreateModal"
                    class="fixed inset-0 bg-[#0F172A]/90 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                    x-cloak>
                    <div class="glass border border-white/10 p-8 sm:p-10 rounded-[2.5rem] max-w-2xl w-full shadow-2xl"
                        @click.away="showCreateModal = false">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-2xl font-black text-white">Nouvelle Session</h3>
                            <button @click="showCreateModal = false"
                                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:text-white transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form action="{{ route('admin.sessions.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Type
                                        d'Examen</label>
                                    <select name="type"
                                        class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                                        <option value="Examen Final" class="bg-[#1E293B]">Examen Final</option>
                                        <option value="Partiel" class="bg-[#1E293B]">Partiel</option>
                                        <option value="Rattrapage" class="bg-[#1E293B]">Rattrapage</option>
                                        <option value="Concours" class="bg-[#1E293B]">Concours</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Centre
                                        d'Examen</label>
                                    <input type="text" name="center" required
                                        class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Titre
                                    /
                                    Description</label>
                                <input type="text" name="title"
                                    class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Date</label>
                                    <input type="date" name="date" required
                                        class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Heure</label>
                                    <input type="time" name="time" required
                                        class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Salle</label>
                                    <input type="text" name="room" required
                                        class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium">
                                </div>
                            </div>

                            <input type="hidden" name="status" value="planned">

                            <div class="flex justify-end gap-6 pt-6">
                                <button type="button" @click="showCreateModal = false"
                                    class="text-sm font-black text-slate-500 hover:text-white transition-colors uppercase tracking-widest">Annuler</button>
                                <button type="submit"
                                    class="bg-indigo-500 text-white px-10 py-4 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20">Créer
                                    la session</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Custom Confirmation Modal -->
                <div x-show="showConfirmModal"
                    class="fixed inset-0 flex items-center justify-center p-4 bg-premium-dark/90 backdrop-blur-md"
                    x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                    <div class="glass w-full max-w-md p-8 rounded-[2.5rem] border-white/10 shadow-2xl transform transition-all"
                        @click.away="showConfirmModal = false" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                        <div class="text-center">
                            <div :class="{
                                'bg-indigo-500/10 text-indigo-400': confirmType === 'info',
                                'bg-rose-500/10 text-rose-400': confirmType === 'danger'
                            }" class="mx-auto w-20 h-20 rounded-3xl flex items-center justify-center mb-6">
                                <i :class="{
                                    'fa-info-circle': confirmType === 'info',
                                    'fa-exclamation-triangle': confirmType === 'danger'
                                }" class="fas text-3xl"></i>
                            </div>

                            <h3 class="text-2xl font-black text-white mb-2" x-text="confirmTitle"></h3>
                            <p class="text-slate-400 text-sm mb-8 leading-relaxed" x-text="confirmMessage"></p>

                            <div class="flex gap-3">
                                <button @click="showConfirmModal = false"
                                    class="flex-1 py-4 bg-white/5 text-white font-bold rounded-2xl hover:bg-white/10 transition-all border border-white/5">
                                    Annuler
                                </button>
                                <button @click="executeConfirm()" :class="{
                                        'bg-indigo-500 hover:bg-indigo-600 shadow-indigo-500/20': confirmType === 'info',
                                        'bg-rose-500 hover:bg-rose-600 shadow-rose-500/20': confirmType === 'danger'
                                    }"
                                    class="flex-1 py-4 text-white font-black rounded-2xl shadow-xl transition-all hover:-translate-y-1">
                                    Confirmer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>