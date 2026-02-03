<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/20">
                    <i class="fas fa-magic text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Générer des Convocations') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Créez des convocations pour vos sessions
                        d'examens.</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8 max-w-5xl mx-auto">
        @if(session('success'))
            <div
                class="p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                <i class="fas fa-check-circle text-lg"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8">
            <!-- Génération individuelle -->
            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-[2.5rem] opacity-10 blur transition duration-500">
                </div>
                <div class="relative glass p-8 sm:p-10 rounded-[2.5rem] border border-white/5">
                    <h2 class="text-xl font-black text-white mb-8 flex items-center gap-3">
                        <i class="fas fa-user-check text-indigo-400"></i>
                        Génération Individuelle
                    </h2>
                    <form action="{{ route('admin.convocations.generate') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Session
                                d'Examen</label>
                            <div class="relative group/select">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-calendar-alt text-slate-600 text-sm group-focus-within/select:text-indigo-400 transition-colors"></i>
                                </div>
                                <select name="exam_session_id"
                                    class="block w-full pl-12 pr-10 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer"
                                    required>
                                    <option value="" class="bg-[#1E293B]">Sélectionnez une session</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->id }}" class="bg-[#1E293B]">
                                            {{ ucfirst($session->type) }} - {{ $session->center }} -
                                            {{ $session->date->format('d/m/Y') }}
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
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Sélectionner
                                les Étudiants</label>
                            <div class="relative group/select">
                                <select name="student_ids[]" multiple
                                    class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium h-64 scrollbar-thin scrollbar-thumb-indigo-500/20 scrollbar-track-transparent"
                                    required>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}"
                                            class="bg-[#1E293B] py-2 px-4 hover:bg-indigo-500/20">
                                            {{ $student->matricule }} - {{ $student->first_name }} {{ $student->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-2 ml-4">
                                <i class="fas fa-info-circle mr-1"></i> Maintenez Ctrl (Cmd) pour la sélection multiple
                            </p>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="bg-indigo-500 text-white px-10 py-4 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20 flex items-center gap-3">
                                <i class="fas fa-file-pdf"></i>
                                Générer les convocations
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Génération en masse -->
            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-pink-500 rounded-[2.5rem] opacity-10 blur transition duration-500">
                </div>
                <div class="relative glass p-8 sm:p-10 rounded-[2.5rem] border border-white/5">
                    <h2 class="text-xl font-black text-white mb-8 flex items-center gap-3">
                        <i class="fas fa-users text-purple-400"></i>
                        Génération en Masse
                    </h2>
                    <form action="{{ route('admin.convocations.bulk-generate') }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Session
                                d'Examen</label>
                            <div class="relative group/select">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-calendar-alt text-slate-600 text-sm group-focus-within/select:text-purple-400 transition-colors"></i>
                                </div>
                                <select name="exam_session_id"
                                    class="block w-full pl-12 pr-10 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer"
                                    required>
                                    <option value="" class="bg-[#1E293B]">Sélectionnez une session</option>
                                    @foreach($sessions as $session)
                                        <option value="{{ $session->id }}" class="bg-[#1E293B]">
                                            {{ ucfirst($session->type) }} - {{ $session->center }} -
                                            {{ $session->date->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-600">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Critères
                                de génération</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label
                                    class="relative flex items-center p-6 bg-white/5 border border-white/10 rounded-2xl cursor-pointer hover:bg-white/10 transition-all group/radio">
                                    <input type="radio" name="generate_for" value="all"
                                        class="w-5 h-5 text-purple-500 bg-white/5 border-white/10 focus:ring-purple-500 focus:ring-offset-0"
                                        required>
                                    <div class="ml-4">
                                        <span class="block text-sm font-black text-white">Tous les étudiants</span>
                                        <span
                                            class="block text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Générer
                                            pour tous les actifs</span>
                                    </div>
                                </label>
                                <label
                                    class="relative flex items-center p-6 bg-white/5 border border-white/10 rounded-2xl cursor-pointer hover:bg-white/10 transition-all group/radio">
                                    <input type="radio" name="generate_for" value="paid"
                                        class="w-5 h-5 text-purple-500 bg-white/5 border-white/10 focus:ring-purple-500 focus:ring-offset-0">
                                    <div class="ml-4">
                                        <span class="block text-sm font-black text-white">Étudiants en règle</span>
                                        <span
                                            class="block text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Seulement
                                            ceux ayant payé</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="bg-purple-500 text-white px-10 py-4 rounded-2xl font-black hover:bg-purple-600 transition-all shadow-xl shadow-purple-500/20 flex items-center gap-3">
                                <i class="fas fa-rocket"></i>
                                Lancer la génération en masse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>