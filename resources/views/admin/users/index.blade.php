<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-users-cog text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Gestion des Utilisateurs') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Administrez les accès et les rôles de la
                        plateforme.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center gap-2 bg-indigo-500 text-white px-6 py-3 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20 hover:-translate-y-1">
                    <i class="fas fa-plus text-sm"></i>
                    Nouvel Utilisateur
                </a>
                <a href="{{ route('admin.users.export-pdf') }}"
                    class="inline-flex items-center gap-2 bg-white/5 border border-white/10 text-white px-6 py-3 rounded-2xl font-black hover:bg-white/10 transition-all">
                    <i class="fas fa-file-pdf text-rose-400"></i>
                    Exporter PDF
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div class="space-y-8" x-data="{ 
        showBadgeModal: false, 
        showScoreModal: false, 
        showDeleteModal: false,
        selectedUser: null,
        selectedUserName: '',
        currentScore: 50,
        deleteFormId: null,
        resetStates() {
            this.showBadgeModal = false;
            this.showScoreModal = false;
            this.showDeleteModal = false;
        },
        openBadgeModal(id, name) {
            this.resetStates();
            this.$nextTick(() => {
                this.selectedUser = id;
                this.selectedUserName = name;
                this.showBadgeModal = true;
            });
        },
        openScoreModal(id, name, score) {
            this.resetStates();
            this.$nextTick(() => {
                this.selectedUser = id;
                this.selectedUserName = name;
                this.currentScore = score;
                this.showScoreModal = true;
            });
        },
        openDeleteModal(formId) {
            this.resetStates();
            this.$nextTick(() => {
                this.deleteFormId = formId;
                this.showDeleteModal = true;
            });
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

        <!-- Filters & Search -->
        <div class="glass p-6 rounded-[2rem] border border-white/5">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative group/input">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-search text-slate-600 text-sm group-focus-within/input:text-indigo-400 transition-colors"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher par nom ou email..."
                        class="block w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium">
                </div>
                <div class="w-full md:w-64 relative group/select">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-filter text-slate-600 text-sm group-focus-within/select:text-indigo-400 transition-colors"></i>
                    </div>
                    <select name="role"
                        class="block w-full pl-12 pr-10 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                        <option value="" class="bg-[#1E293B]">Tous les rôles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }} class="bg-[#1E293B]">
                            Admin</option>
                        <option value="comptable" {{ request('role') === 'comptable' ? 'selected' : '' }}
                            class="bg-[#1E293B]">Comptable</option>
                        <option value="scolarite" {{ request('role') === 'scolarite' ? 'selected' : '' }}
                            class="bg-[#1E293B]">Scolarité</option>
                        <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}
                            class="bg-[#1E293B]">Étudiant</option>
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

        <div class="glass rounded-[2.5rem] border border-white/5 shadow-2xl">
            <div class="overflow-x-auto pb-4 custom-scrollbar">
                <table class="min-w-full min-w-[1100px] text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-6">Utilisateur</th>
                            <th class="px-8 py-6">Rôle</th>
                            <th class="px-8 py-6">Confiance</th>
                            <th class="px-8 py-6">Badges</th>
                            <th class="px-8 py-6">Statut</th>
                            <th class="px-8 py-6 text-right min-w-[220px]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($users as $user)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center text-white font-black shadow-lg group-hover:scale-110 transition-transform">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-white">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-500 font-medium">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($user->roles as $role)
                                            <span
                                                class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <button
                                        @click.stop="openScoreModal({{ $user->id }}, {{ json_encode($user->name) }}, {{ $user->trust_score }})"
                                        class="flex items-center gap-2 hover:bg-white/5 p-1 rounded-lg transition-colors">
                                        <div class="w-12 h-2 bg-white/5 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-rose-500 via-amber-500 to-emerald-500"
                                                style="width: {{ $user->trust_score }}%"></div>
                                        </div>
                                        <span class="text-xs font-black text-white">{{ $user->trust_score }}</span>
                                    </button>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-wrap gap-1 items-center">
                                        @foreach($user->badges as $badge)
                                            <span
                                                class="w-6 h-6 rounded-lg bg-{{ $badge->color }}-500/20 text-{{ $badge->color }}-400 flex items-center justify-center border border-{{ $badge->color }}-500/30"
                                                title="{{ $badge->name }}: {{ $badge->description }}">
                                                <i class="{{ $badge->icon }} text-[10px]"></i>
                                            </span>
                                        @endforeach
                                        <button
                                            @click.stop="openBadgeModal({{ $user->id }}, {{ json_encode($user->name) }})"
                                            class="w-6 h-6 rounded-lg bg-white/5 text-slate-500 flex items-center justify-center border border-white/10 hover:text-white hover:bg-white/10 transition-all">
                                            <i class="fas fa-plus text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($user->status === 'active')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            Actif
                                        </span>
                                    @elseif($user->status === 'pending')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full"></span>
                                            En attente
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                            <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                            Rejeté
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-end items-center gap-2 flex-nowrap">
                                        @if($user->id !== auth()->id() && !$user->hasRole('admin'))
                                            <form action="{{ route('admin.impersonate', $user) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-all"
                                                    title="Se connecter en tant que...">
                                                    <i class="fas fa-user-secret text-xs"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 transition-all"
                                            title="Modifier">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>

                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400 hover:text-amber-400 hover:bg-amber-500/10 transition-all"
                                                title="{{ $user->status === 'active' ? 'Désactiver' : 'Activer' }}">
                                                <i
                                                    class="fas {{ $user->status === 'active' ? 'fa-user-slash' : 'fa-user-check' }} text-xs"></i>
                                            </button>
                                        </form>

                                        <form id="reset-form-{{ $user->id }}"
                                            action="{{ route('admin.users.force-reset', $user) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="button"
                                                @click.stop="openDeleteModal('reset-form-{{ $user->id }}')"
                                                class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400 hover:text-purple-400 hover:bg-purple-500/10 transition-all"
                                                title="Reset Password">
                                                <i class="fas fa-key text-xs"></i>
                                            </button>
                                        </form>

                                        @if($user->id !== auth()->id())
                                            <form id="delete-form-{{ $user->id }}"
                                                action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    @click.stop="openDeleteModal('delete-form-{{ $user->id }}')"
                                                    class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all"
                                                    title="Supprimer">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-8 py-6 bg-white/5 border-t border-white/5">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Modals (Teleported to Body) -->
        <template x-teleport="body">
            <div class="relative z-[9999]">
                <!-- Badge Modal -->
                <div x-show="showBadgeModal"
                    class="fixed inset-0 flex items-center justify-center p-4 bg-premium-dark/80 backdrop-blur-sm"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
                    <div class="glass w-full max-w-md p-8 rounded-[2rem] border-white/10 shadow-2xl"
                        @click.away="showBadgeModal = false" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                        <h3 class="text-2xl font-black text-white mb-2">Assigner un Badge</h3>
                        <p class="text-slate-400 text-sm mb-6">Utilisateur : <span class="text-white font-bold"
                                x-text="selectedUserName"></span></p>

                        <form :action="'/admin/users/' + selectedUser + '/badges'" method="POST" class="space-y-6">
                            @csrf
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Sélectionner
                                    un
                                    badge</label>
                                <div class="relative group">
                                    <select name="badge_id"
                                        class="w-full bg-white/5 border border-white/10 rounded-xl text-white p-4 pr-10 focus:ring-2 focus:ring-indigo-500 focus:outline-none appearance-none cursor-pointer">
                                        <option value="" class="bg-premium-dark">-- Choisir un badge --</option>
                                        @foreach($badges as $badge)
                                            <option value="{{ $badge->id }}" class="bg-premium-dark">{{ $badge->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500 group-focus-within:text-indigo-500 transition-colors">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <button type="button" @click="showBadgeModal = false"
                                        class="flex-1 py-4 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition-all">Annuler</button>
                                    <button type="submit"
                                        class="flex-1 py-4 bg-indigo-500 text-white font-black rounded-xl shadow-lg shadow-indigo-500/20 hover:bg-indigo-600 transition-all">Assigner</button>
                                </div>
                        </form>
                    </div>
                </div>

                <!-- Score Modal -->
                <div x-show="showScoreModal"
                    class="fixed inset-0 flex items-center justify-center p-4 bg-premium-dark/80 backdrop-blur-sm"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
                    <div class="glass w-full max-w-md p-8 rounded-[2rem] border-white/10 shadow-2xl"
                        @click.away="showScoreModal = false" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-4">
                        <h3 class="text-2xl font-black text-white mb-2">Score de Confiance</h3>
                        <p class="text-slate-400 text-sm mb-6">Utilisateur : <span class="text-white font-bold"
                                x-text="selectedUserName"></span></p>

                        <form :action="'/admin/users/' + selectedUser + '/trust-score'" method="POST" class="space-y-6">
                            @csrf
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Niveau
                                        de
                                        confiance</label>
                                    <span class="text-2xl font-black text-white" x-text="currentScore + '%'"></span>
                                </div>
                                <input type="range" name="trust_score" x-model="currentScore" min="0" max="100"
                                    class="w-full h-2 bg-white/5 rounded-full appearance-none cursor-pointer accent-indigo-500">
                                <div
                                    class="flex justify-between text-[10px] font-bold text-slate-600 uppercase tracking-widest">
                                    <span>Risqué</span>
                                    <span>Neutre</span>
                                    <span>Fiable</span>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <button type="button" @click="showScoreModal = false"
                                    class="flex-1 py-4 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition-all">Annuler</button>
                                <button type="submit"
                                    class="flex-1 py-4 bg-indigo-500 text-white font-black rounded-xl shadow-lg shadow-indigo-500/20 hover:bg-indigo-600 transition-all">Mettre
                                    à jour</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delete/Confirm Modal -->
                <div x-show="showDeleteModal"
                    class="fixed inset-0 flex items-center justify-center p-4 bg-premium-dark/80 backdrop-blur-sm"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
                    <div class="glass w-full max-w-sm p-8 rounded-[2rem] border-white/10 shadow-2xl text-center"
                        @click.away="showDeleteModal = false" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                        <div
                            class="w-16 h-16 rounded-2xl bg-rose-500/10 flex items-center justify-center mx-auto mb-6 text-rose-400">
                            <i class="fas fa-exclamation-triangle text-3xl"></i>
                        </div>

                        <h3 class="text-2xl font-black text-white mb-2">Êtes-vous sûr ?</h3>
                        <p class="text-slate-400 text-sm mb-8">Cette action est irréversible. Voulez-vous vraiment
                            continuer ?</p>

                        <div class="flex gap-3">
                            <button type="button" @click="showDeleteModal = false"
                                class="flex-1 py-4 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition-all">
                                Annuler
                            </button>
                            <button type="button" @click="document.getElementById(deleteFormId).submit()"
                                class="flex-1 py-4 bg-rose-500 text-white font-black rounded-xl shadow-lg shadow-rose-500/20 hover:bg-rose-600 transition-all">
                                Confirmer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>