<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-lg shadow-amber-500/20">
                    <i class="fas fa-user-edit text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Modifier l\'Utilisateur') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Modification du compte de <span
                            class="text-amber-400 font-bold">{{ $user->name }}</span></p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 bg-white/5 border border-white/10 text-white px-6 py-3 rounded-2xl font-black hover:bg-white/10 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <!-- Form Card -->
        <div class="relative group">
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-[2.5rem] opacity-10 blur transition duration-500">
            </div>
            <div class="relative glass p-8 sm:p-10 rounded-[2.5rem] border border-white/5">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <!-- Name -->
                    <div class="space-y-2">
                        <label for="name"
                            class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Nom
                            complet</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-user text-slate-600 text-sm group-focus-within/input:text-indigo-400 transition-colors"></i>
                            </div>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                                class="block w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium"
                                placeholder="Jean Dupont" required autofocus />
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email"
                            class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Adresse
                            Email</label>
                        <div class="relative group/input">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-envelope text-slate-600 text-sm group-focus-within/input:text-blue-400 transition-colors"></i>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                                class="block w-full pl-12 pr-4 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-medium"
                                placeholder="utilisateur@email.mg" required />
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Role Selection -->
                        <div class="space-y-2">
                            <label for="role"
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Rôle</label>
                            <div class="relative group/select">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-user-tag text-slate-600 text-sm group-focus-within/select:text-amber-400 transition-colors"></i>
                                </div>
                                <select id="role" name="role"
                                    class="block w-full pl-12 pr-10 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                                    <option value="student" {{ $user->hasRole('student') ? 'selected' : '' }}
                                        class="bg-[#1E293B]">🎓 Étudiant</option>
                                    <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}
                                        class="bg-[#1E293B]">👑 Administrateur</option>
                                    <option value="comptable" {{ $user->hasRole('comptable') ? 'selected' : '' }}
                                        class="bg-[#1E293B]">💰 Comptable</option>
                                    <option value="scolarite" {{ $user->hasRole('scolarite') ? 'selected' : '' }}
                                        class="bg-[#1E293B]">📚 Scolarité</option>
                                    <option value="controleur" {{ $user->hasRole('controleur') ? 'selected' : '' }}
                                        class="bg-[#1E293B]">🔍 Contrôleur</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-600">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('role')" />
                        </div>

                        <!-- Status Selection -->
                        <div class="space-y-2">
                            <label for="status"
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Statut</label>
                            <div class="relative group/select">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-toggle-on text-slate-600 text-sm group-focus-within/select:text-emerald-400 transition-colors"></i>
                                </div>
                                <select id="status" name="status"
                                    class="block w-full pl-12 pr-10 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}
                                        class="bg-[#1E293B]">🟢 Actif</option>
                                    <option value="pending" {{ $user->status === 'pending' ? 'selected' : '' }}
                                        class="bg-[#1E293B]">🟡 En attente</option>
                                    <option value="rejected" {{ $user->status === 'rejected' ? 'selected' : '' }}
                                        class="bg-[#1E293B]">🔴 Rejeté</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-600">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-6 pt-8 border-t border-white/5">
                        <a href="{{ route('admin.users.index') }}"
                            class="text-sm font-black text-slate-500 hover:text-white transition-colors uppercase tracking-widest">
                            Annuler
                        </a>
                        <button type="submit"
                            class="bg-amber-500 text-white px-10 py-4 rounded-2xl font-black hover:bg-amber-600 transition-all shadow-xl shadow-amber-500/20 flex items-center gap-3">
                            <i class="fas fa-sync-alt"></i>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>