<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-black text-3xl text-white leading-tight">
                        {{ __('Gestion des Résultats') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Saisissez et publiez les notes des examens.</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <button onclick="openImportModal()"
                    class="inline-flex items-center gap-2 bg-white/5 border border-white/10 text-white px-6 py-3 rounded-2xl font-black hover:bg-white/10 transition-all">
                    <i class="fas fa-file-import text-sm text-emerald-400"></i>
                    Importer CSV
                </button>
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 bg-indigo-500 text-white px-6 py-3 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20">
                    <i class="fas fa-plus text-sm"></i>
                    Saisie Manuelle
                </button>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Filter Bar -->
        <div class="glass p-6 rounded-[2rem] border border-white/5 flex flex-wrap gap-6 items-end">
            <div class="flex-1 min-w-[300px] space-y-2">
                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Filtrer par
                    Session</label>
                <form action="{{ route('admin.results.index') }}" method="GET" id="filterForm"
                    class="relative group/select">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i
                            class="fas fa-calendar-alt text-slate-600 text-sm group-focus-within/select:text-indigo-400 transition-colors"></i>
                    </div>
                    <select name="session_id" onchange="this.form.submit()"
                        class="block w-full pl-12 pr-10 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                        <option value="" class="bg-[#1E293B]">Toutes les sessions</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ request('session_id') == $session->id ? 'selected' : '' }}
                                class="bg-[#1E293B]">
                                {{ $session->type }} - {{ $session->date->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-600">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </form>
            </div>
            @if(request('session_id'))
                <form action="{{ route('admin.results.publish') }}" method="POST">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ request('session_id') }}">
                    <button type="submit"
                        class="bg-amber-500 text-white px-8 py-3 rounded-xl font-black hover:bg-amber-600 transition-all shadow-xl shadow-amber-500/20 flex items-center gap-3">
                        <i class="fas fa-bullhorn"></i>
                        Publier les résultats
                    </button>
                </form>
            @endif
        </div>

        <!-- Results Table -->
        <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full min-w-[900px] text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-6">Étudiant</th>
                            <th class="px-8 py-6">Matière / Session</th>
                            <th class="px-8 py-6">Note</th>
                            <th class="px-8 py-6 text-center">Statut</th>
                            <th class="px-8 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($results as $result)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-black text-white group-hover:text-indigo-400 transition-colors">{{ $result->student->name }}</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">{{ $result->student->matricule }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-white">{{ $result->subject }}</span>
                                        <span
                                            class="text-[10px] text-slate-500 font-black uppercase tracking-widest mt-1">{{ $result->examSession->type }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="text-2xl font-black {{ $result->grade >= 10 ? 'text-emerald-400' : 'text-rose-400' }}">
                                            {{ number_format($result->grade, 2) }}
                                        </div>
                                        <span class="text-[10px] font-black text-slate-600 uppercase">/ 20</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex justify-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                                    {{ $result->status === 'draft' ? 'bg-white/5 text-slate-500 border border-white/10' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' }}
                                                ">
                                            {{ $result->status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <button onclick="openEditModal({{ $result->toJson() }})"
                                        class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 transition-all border border-white/10">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div
                                            class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center text-slate-700">
                                            <i class="fas fa-clipboard-list text-4xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucun résultat
                                            enregistré</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($results->hasPages())
                <div class="px-8 py-6 bg-white/5 border-t border-white/5">
                    {{ $results->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal"
        class="fixed inset-0 bg-[#0F172A]/90 backdrop-blur-sm flex items-center justify-center z-50 p-4 hidden">
        <div class="glass border border-white/10 p-8 sm:p-10 rounded-[2.5rem] max-w-lg w-full shadow-2xl">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-black text-white">Saisie de Note</h3>
                <button onclick="closeCreateModal()"
                    class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:text-white transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin.results.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label
                        class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Étudiant</label>
                    <select name="student_id" required
                        class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                        @foreach(App\Models\Student::all() as $student)
                            <option value="{{ $student->id }}" class="bg-[#1E293B]">{{ $student->name }}
                                ({{ $student->matricule }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label
                        class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Session</label>
                    <select name="exam_session_id" required
                        class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none font-medium cursor-pointer">
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" class="bg-[#1E293B]">{{ $session->type }} -
                                {{ $session->date->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label
                        class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Matière</label>
                    <input type="text" name="subject" required
                        class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest ml-4">Note (sur
                        20)</label>
                    <input type="number" name="grade" step="0.01" min="0" max="20" required
                        class="block w-full px-6 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-medium">
                </div>

                <div class="flex justify-end gap-6 pt-6">
                    <button type="button" onclick="closeCreateModal()"
                        class="text-sm font-black text-slate-500 hover:text-white transition-colors uppercase tracking-widest">Annuler</button>
                    <button type="submit"
                        class="bg-indigo-500 text-white px-10 py-4 rounded-2xl font-black hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() { document.getElementById('createModal').classList.remove('hidden'); }
        function closeCreateModal() { document.getElementById('createModal').classList.add('hidden'); }
    </script>
</x-app-layout>