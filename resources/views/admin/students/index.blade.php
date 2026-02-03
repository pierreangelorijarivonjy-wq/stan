<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                    <i class="ph-fill ph-student text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-heading font-black text-3xl text-white leading-tight">
                        {{ __('Gestion des Étudiants') }}
                    </h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Suivez les inscriptions et le statut académique.
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button"
                    class="inline-flex items-center gap-2 bg-indigo-500 text-white px-5 py-3 rounded-2xl font-bold hover:bg-indigo-600 transition-all shadow-xl shadow-indigo-500/20 hover:-translate-y-1">
                    <i class="ph-bold ph-bank text-lg"></i>
                    <span class="hidden sm:inline">Rapprochement</span>
                </button>
                <a href="{{ route('admin.export.students.csv') }}"
                    class="inline-flex items-center gap-2 bg-emerald-500 text-white px-5 py-3 rounded-2xl font-bold hover:bg-emerald-600 transition-all shadow-xl shadow-emerald-500/20 hover:-translate-y-1">
                    <i class="ph-bold ph-file-csv text-lg"></i>
                    <span class="hidden sm:inline">Exporter</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8" x-data="{ 
        showDetails: false, 
        selectedStudent: null,
        openDetails(student) {
            this.selectedStudent = student;
            this.showDetails = true;
        }
    }">
        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Students -->
            <div class="glass rounded-[2rem] p-6 border border-white/5 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ph-fill ph-users-three text-6xl text-blue-400"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Étudiants</p>
                    <h3 class="text-3xl font-black text-white">{{ $stats['total'] }}</h3>
                    <div class="mt-2 flex items-center gap-2 text-xs font-medium text-emerald-400">
                        <i class="ph-bold ph-trend-up"></i>
                        <span>+12% ce mois</span>
                    </div>
                </div>
            </div>

            <!-- Active Students -->
            <div class="glass rounded-[2rem] p-6 border border-white/5 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ph-fill ph-check-circle text-6xl text-emerald-400"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Actifs</p>
                    <h3 class="text-3xl font-black text-white">{{ $stats['active'] }}</h3>
                    <div class="mt-2 flex items-center gap-2 text-xs font-medium text-slate-400">
                        <span>En règle</span>
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            <div class="glass rounded-[2rem] p-6 border border-white/5 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ph-fill ph-currency-circle-dollar text-6xl text-amber-400"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Paiements Attente</p>
                    <h3 class="text-3xl font-black text-white">{{ $stats['pending_payment'] }}</h3>
                    <div class="mt-2 flex items-center gap-2 text-xs font-medium text-amber-400">
                        <i class="ph-bold ph-warning-circle"></i>
                        <span>Action requise</span>
                    </div>
                </div>
            </div>

            <!-- Reconciliation Rate -->
            <div class="glass rounded-[2rem] p-6 border border-white/5 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="ph-fill ph-arrows-left-right text-6xl text-purple-400"></i>
                </div>
                <div class="relative z-10">
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Taux Rapprochement</p>
                    <h3 class="text-3xl font-black text-white">{{ $stats['reconciliation_rate'] }}%</h3>
                    <div class="w-full bg-white/10 h-1.5 rounded-full mt-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-full rounded-full"
                            style="width: {{ $stats['reconciliation_rate'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="glass rounded-[2.5rem] p-2 border border-white/5 flex flex-col md:flex-row gap-2">
            <form action="{{ route('admin.students.index') }}" method="GET" class="contents">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i
                            class="ph-bold ph-magnifying-glass text-slate-500 group-focus-within:text-blue-400 transition-colors"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Rechercher par nom, matricule..."
                        class="block w-full pl-12 pr-4 py-4 bg-transparent border-none text-white placeholder-slate-600 focus:ring-0 text-sm font-medium">
                </div>
                <div class="h-px md:h-auto md:w-px bg-white/10 mx-2"></div>
                <div class="flex gap-2 p-1">
                    <select name="status" onchange="this.form.submit()"
                        class="bg-white/5 border border-white/10 rounded-2xl text-white text-sm font-medium px-6 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none cursor-pointer hover:bg-white/10">
                        <option value="" class="bg-[#0A0B3B]">Tous statuts</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}
                            class="bg-[#0A0B3B]">Actif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}
                            class="bg-[#0A0B3B]">Inactif</option>
                    </select>
                    <button type="submit"
                        class="bg-blue-500 text-white px-6 py-3 rounded-2xl hover:bg-blue-600 transition-all shadow-lg shadow-blue-500/20 font-bold">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Content Area -->
        <div class="glass rounded-[2.5rem] overflow-hidden border border-white/5 shadow-2xl">

            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-500 text-[10px] uppercase tracking-[0.2em] font-black">
                            <th class="px-8 py-6 cursor-pointer hover:text-white transition-colors"
                                onclick="window.location='{{ route('admin.students.index', array_merge(request()->query(), ['sort' => 'first_name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}'">
                                <div class="flex items-center gap-2">
                                    Étudiant
                                    @if(request('sort') === 'first_name')
                                        <i
                                            class="ph-bold {{ request('direction') === 'asc' ? 'ph-caret-up' : 'ph-caret-down' }} text-blue-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-8 py-6 cursor-pointer hover:text-white transition-colors"
                                onclick="window.location='{{ route('admin.students.index', array_merge(request()->query(), ['sort' => 'matricule', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}'">
                                <div class="flex items-center gap-2">
                                    Matricule
                                    @if(request('sort') === 'matricule')
                                        <i
                                            class="ph-bold {{ request('direction') === 'asc' ? 'ph-caret-up' : 'ph-caret-down' }} text-blue-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-8 py-6">Cours</th>
                            <th class="px-8 py-6">Paiement</th>
                            <th class="px-8 py-6 cursor-pointer hover:text-white transition-colors"
                                onclick="window.location='{{ route('admin.students.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}'">
                                <div class="flex items-center gap-2">
                                    Statut
                                    @if(request('sort') === 'status')
                                        <i
                                            class="ph-bold {{ request('direction') === 'asc' ? 'ph-caret-up' : 'ph-caret-down' }} text-blue-400"></i>
                                    @endif
                                </div>
                            </th>
                            <th class="px-8 py-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($students as $student)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/20 to-indigo-500/20 flex items-center justify-center text-blue-400 font-black shadow-lg group-hover:scale-110 transition-transform border border-blue-500/20">
                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-white">{{ $student->first_name }}
                                                {{ $student->last_name }}</div>
                                            <div class="text-xs text-slate-500 font-medium">{{ $student->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="text-xs font-mono font-bold text-slate-400 bg-white/5 px-2 py-1 rounded-lg border border-white/5">
                                        {{ $student->matricule }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse($student->courses as $course)
                                            <span
                                                class="px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider bg-blue-500/10 text-blue-400 border border-blue-500/20"
                                                title="{{ $course->title }}">
                                                {{ Str::limit($course->title, 10) }}
                                            </span>
                                        @empty
                                            <span
                                                class="text-[10px] text-slate-600 font-bold uppercase tracking-widest italic">Aucun
                                                cours</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @php $latestPayment = $student->payments->sortByDesc('created_at')->first(); @endphp
                                    @if($latestPayment)
                                        <div class="flex flex-col gap-1">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg text-[10px] font-black uppercase {{ $latestPayment->status === 'paid' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($latestPayment->status === 'pending' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                                                {{ $latestPayment->status }}
                                            </span>
                                            <span
                                                class="text-[9px] text-slate-600 font-bold">{{ $latestPayment->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    @else
                                        <span
                                            class="text-[10px] text-slate-600 font-bold uppercase tracking-widest italic">Aucun
                                            paiement</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $student->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                        <span
                                            class="w-1.5 h-1.5 {{ $student->status === 'active' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }} rounded-full"></span>
                                        {{ $student->status === 'active' ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                        <button @click="open = !open" @click.away="open = false"
                                            class="p-2 rounded-xl hover:bg-white/10 text-slate-400 hover:text-white transition-colors">
                                            <i class="ph-bold ph-dots-three-vertical text-xl"></i>
                                        </button>
                                        <div x-show="open" x-transition
                                            class="absolute right-0 mt-2 w-48 bg-[#0A0B3B] border border-white/10 rounded-xl shadow-2xl z-50 overflow-hidden">
                                            <a href="#"
                                                @click.prevent="openDetails({{ json_encode($student) }}); open = false"
                                                class="block px-4 py-3 text-sm text-slate-300 hover:bg-white/5 hover:text-white font-medium transition-colors">
                                                <i class="ph-bold ph-user mr-2"></i> Détails
                                            </a>
                                            <a href="{{ route('admin.students.show', $student) }}"
                                                class="block px-4 py-3 text-sm text-slate-300 hover:bg-white/5 hover:text-white font-medium transition-colors">
                                                <i class="ph-bold ph-eye mr-2"></i> Voir Profil
                                            </a>
                                            <div class="h-px bg-white/5 my-1"></div>
                                            <a href="#"
                                                class="block px-4 py-3 text-sm text-emerald-400 hover:bg-emerald-500/10 font-medium transition-colors">
                                                <i class="ph-bold ph-check-circle mr-2"></i> Valider Paiement
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div
                                            class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center text-slate-700">
                                            <i class="ph-fill ph-student text-4xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucun étudiant
                                            trouvé</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4 p-4">
                @forelse($students as $student)
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/5 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/20 to-indigo-500/20 flex items-center justify-center text-blue-400 font-black border border-blue-500/20">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-white">{{ $student->first_name }}
                                        {{ $student->last_name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $student->matricule }}</div>
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[10px] font-black uppercase {{ $student->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                {{ $student->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-white/5 rounded-lg p-2">
                                <span class="text-slate-500 block mb-1">Paiement</span>
                                @php $latestPayment = $student->payments->sortByDesc('created_at')->first(); @endphp
                                @if($latestPayment)
                                    <span
                                        class="font-bold {{ $latestPayment->status === 'paid' ? 'text-emerald-400' : 'text-amber-400' }}">
                                        {{ ucfirst($latestPayment->status) }}
                                    </span>
                                @else
                                    <span class="text-slate-600 italic">Aucun</span>
                                @endif
                            </div>
                            <div class="bg-white/5 rounded-lg p-2">
                                <span class="text-slate-500 block mb-1">Cours</span>
                                <span class="font-bold text-white">{{ $student->courses->count() }} inscrit(s)</span>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <button @click="openDetails({{ json_encode($student) }})"
                                class="flex-1 py-2 bg-white/5 rounded-xl text-xs font-bold text-slate-300 hover:bg-white/10 transition-colors">
                                Aperçu
                            </button>
                            <a href="{{ route('admin.students.show', $student) }}"
                                class="flex-1 py-2 bg-blue-500/10 border border-blue-500/20 rounded-xl text-xs font-bold text-blue-400 hover:bg-blue-500/20 transition-colors text-center">
                                Détails
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">Aucun étudiant trouvé</p>
                    </div>
                @endforelse
            </div>

            @if($students->hasPages())
                <div class="px-8 py-6 bg-white/5 border-t border-white/5">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Student Details Modal -->
    <div x-show="showDetails" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <!-- Backdrop -->
        <div x-show="showDetails" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="showDetails = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="showDetails" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-[2rem] bg-[#0A0B3B] border border-white/10 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                <div class="bg-gradient-to-br from-blue-600/20 to-purple-600/20 p-6 border-b border-white/5">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg text-2xl font-black">
                            <span
                                x-text="selectedStudent ? selectedStudent.first_name.charAt(0) + selectedStudent.last_name.charAt(0) : ''"></span>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white"
                                x-text="selectedStudent ? selectedStudent.first_name + ' ' + selectedStudent.last_name : ''">
                            </h3>
                            <p class="text-blue-400 font-mono text-sm"
                                x-text="selectedStudent ? selectedStudent.matricule : ''"></p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/5 rounded-xl p-3">
                            <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Email</p>
                            <p class="text-sm text-white font-medium truncate"
                                x-text="selectedStudent && selectedStudent.user ? selectedStudent.user.email : 'N/A'">
                            </p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-3">
                            <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Téléphone</p>
                            <p class="text-sm text-white font-medium"
                                x-text="selectedStudent ? selectedStudent.phone : 'N/A'"></p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-3">
                            <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Date Inscription
                            </p>
                            <p class="text-sm text-white font-medium"
                                x-text="selectedStudent ? new Date(selectedStudent.created_at).toLocaleDateString() : ''">
                            </p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-3">
                            <p class="text-xs text-slate-500 uppercase tracking-wider font-bold mb-1">Statut</p>
                            <span
                                class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg text-[10px] font-black uppercase"
                                :class="selectedStudent && selectedStudent.status === 'active' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400'">
                                <span
                                    x-text="selectedStudent && selectedStudent.status === 'active' ? 'Actif' : 'Inactif'"></span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white/5 px-6 py-4 flex flex-row-reverse gap-3">
                    <a :href="selectedStudent ? '{{ url('admin/students') }}/' + selectedStudent.id : '#'"
                        class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-blue-500 sm:w-auto transition-colors">
                        Voir Profil Complet
                    </a>
                    <button type="button"
                        class="mt-3 inline-flex w-full justify-center rounded-xl bg-white/5 px-3 py-2 text-sm font-bold text-slate-300 shadow-sm ring-1 ring-inset ring-white/10 hover:bg-white/10 sm:mt-0 sm:w-auto transition-colors"
                        @click="showDetails = false">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>