<x-app-layout>
    <div class="min-h-screen bg-[#0F172A] py-8 px-4 sm:px-6 lg:px-8 font-['Nunito']">
        <div class="max-w-7xl mx-auto space-y-12">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <h1 class="text-4xl font-black text-white tracking-tight font-heading">Mes Cours</h1>
                    <p class="text-slate-500 mt-2 font-medium text-lg">Explorez vos modules d'apprentissage et
                        progressez à votre rythme.</p>
                </div>

                <!-- Search & Filter Bar -->
                <form action="{{ route('courses') }}" method="GET"
                    class="w-full md:w-auto flex flex-col md:flex-row gap-4">
                    <div class="relative group flex-1 md:w-80">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i
                                class="ph-bold ph-magnifying-glass text-slate-500 group-focus-within:text-indigo-400 transition-colors"></i>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}"
                            class="block w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-bold shadow-xl"
                            placeholder="Rechercher un cours...">
                    </div>

                    <div class="flex gap-2">
                        <select name="level" onchange="this.form.submit()"
                            class="bg-white/5 border border-white/10 rounded-2xl text-white font-bold px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all cursor-pointer hover:bg-white/10">
                            <option value="" class="bg-[#0F172A]">Niveau</option>
                            @foreach($levels as $lvl)
                                <option value="{{ $lvl }}" {{ request('level') == $lvl ? 'selected' : '' }}
                                    class="bg-[#0F172A]">{{ $lvl }}</option>
                            @endforeach
                        </select>

                        <select name="category" onchange="this.form.submit()"
                            class="bg-white/5 border border-white/10 rounded-2xl text-white font-bold px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all cursor-pointer hover:bg-white/10">
                            <option value="" class="bg-[#0F172A]">Catégorie</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}
                                    class="bg-[#0F172A]">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            @if(empty(request('q')) && empty(request('level')) && empty(request('category')))
                <!-- Continue Learning (Hero) -->
                <div class="relative group">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-purple-600 to-cyan-500 rounded-[2.5rem] opacity-20 group-hover:opacity-30 blur-xl transition duration-1000">
                    </div>
                    <div
                        class="relative bg-[#1E293B]/50 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] overflow-hidden">
                        <div class="flex flex-col lg:flex-row">
                            <div class="lg:w-5/12 relative overflow-hidden h-64 lg:h-auto">
                                <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1200&h=800&fit=crop"
                                    alt="Featured Course"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-[#1E293B] via-transparent to-transparent hidden lg:block">
                                </div>
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-[#1E293B] via-transparent to-transparent lg:hidden">
                                </div>
                            </div>
                            <div class="lg:w-7/12 p-8 lg:p-12 flex flex-col justify-center">
                                <div
                                    class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-500/10 text-indigo-400 rounded-lg text-[10px] font-black tracking-widest uppercase mb-6 border border-indigo-500/20 w-fit">
                                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                                    En cours
                                </div>
                                <h2 class="text-3xl md:text-4xl font-black text-white mb-4 leading-tight font-heading">
                                    Mathématiques Avancées</h2>
                                <p class="text-slate-400 mb-8 leading-relaxed font-medium">
                                    Continuez votre progression sur l'algèbre linéaire. Prochaine leçon : "Systèmes
                                    d'Équations".
                                </p>

                                <div class="mb-8 bg-white/5 p-4 rounded-2xl border border-white/5">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Progression
                                            globale</span>
                                        <span class="text-sm font-black text-indigo-400">65%</span>
                                    </div>
                                    <div class="w-full bg-black/20 h-2.5 rounded-full overflow-hidden">
                                        <div
                                            class="bg-gradient-to-r from-indigo-500 to-purple-600 h-full w-[65%] rounded-full shadow-[0_0_10px_rgba(99,102,241,0.5)]">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-4">
                                    <a href="{{ route('course.show', 'mathematiques') }}"
                                        class="inline-flex items-center justify-center gap-2 bg-white text-indigo-900 px-8 py-3.5 rounded-xl font-black hover:bg-indigo-50 transition-all shadow-lg shadow-white/5 hover:-translate-y-0.5">
                                        <i class="ph-fill ph-play-circle text-xl"></i>
                                        Reprendre
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Course Grid -->
            <div class="space-y-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-black text-white flex items-center gap-3 font-heading">
                        <i class="ph-duotone ph-books text-indigo-400"></i>
                        Catalogue des Cours
                    </h3>
                    <div class="hidden md:flex gap-2">
                        <button class="p-2 rounded-lg bg-white/10 text-white hover:bg-white/20 transition-colors">
                            <i class="ph-bold ph-squares-four"></i>
                        </button>
                        <button
                            class="p-2 rounded-lg text-slate-500 hover:text-white hover:bg-white/10 transition-colors">
                            <i class="ph-bold ph-list-dashes"></i>
                        </button>
                    </div>
                </div>

                @if(count($courses) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach($courses as $slug => $course)
                            <a href="{{ route('course.show', $slug) }}" class="group relative flex flex-col h-full">
                                <div
                                    class="absolute -inset-0.5 bg-gradient-to-br from-indigo-500/50 to-purple-600/50 rounded-[2rem] opacity-0 group-hover:opacity-100 blur transition duration-500">
                                </div>
                                <div
                                    class="relative flex flex-col h-full bg-[#1E293B] border border-white/5 rounded-[2rem] overflow-hidden hover:translate-y-[-4px] transition-all duration-300 shadow-xl">
                                    <!-- Thumbnail -->
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="{{ $course['image'] }}" alt="{{ $course['title'] }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-[#1E293B] via-transparent to-transparent opacity-80">
                                        </div>

                                        <!-- Badges -->
                                        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                            <span
                                                class="px-2.5 py-1 bg-black/40 backdrop-blur-md text-white rounded-lg text-[10px] font-black border border-white/10 uppercase tracking-wider">
                                                {{ $course['category'] }}
                                            </span>
                                        </div>

                                        <!-- Level Badge -->
                                        <div class="absolute bottom-4 left-4">
                                            @php
                                                $levelColor = match ($course['level']) {
                                                    'Débutant' => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
                                                    'Intermédiaire' => 'text-amber-400 bg-amber-500/10 border-amber-500/20',
                                                    'Avancé' => 'text-rose-400 bg-rose-500/10 border-rose-500/20',
                                                    default => 'text-slate-400 bg-slate-500/10 border-slate-500/20'
                                                };
                                            @endphp
                                            <span
                                                class="px-2.5 py-1 rounded-lg text-[10px] font-black border uppercase tracking-wider {{ $levelColor }}">
                                                {{ $course['level'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6 flex-1 flex flex-col">
                                        <h4
                                            class="text-lg font-black text-white mb-2 leading-snug font-heading group-hover:text-indigo-400 transition-colors line-clamp-2">
                                            {{ $course['title'] }}
                                        </h4>
                                        <p class="text-sm text-slate-500 font-medium mb-4 line-clamp-2 flex-1">
                                            {{ $course['description'] }}
                                        </p>

                                        <div class="flex items-center gap-3 mb-6">
                                            <div
                                                class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-slate-400 text-xs font-bold border border-white/5">
                                                {{ substr($course['instructor'], 0, 1) }}
                                            </div>
                                            <span
                                                class="text-xs text-slate-400 font-bold truncate">{{ $course['instructor'] }}</span>
                                        </div>

                                        <!-- Progress Bar -->
                                        <div class="mt-auto space-y-2">
                                            <div class="flex justify-between items-end">
                                                <span
                                                    class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Progression</span>
                                                <span
                                                    class="text-xs font-black {{ $course['progress'] == 100 ? 'text-emerald-400' : 'text-indigo-400' }}">
                                                    {{ $course['progress'] }}%
                                                </span>
                                            </div>
                                            <div class="w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full transition-all duration-1000 {{ $course['progress'] == 100 ? 'bg-emerald-500' : 'bg-indigo-500' }}"
                                                    style="width: {{ $course['progress'] }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div
                            class="w-24 h-24 bg-white/5 rounded-[2rem] flex items-center justify-center text-slate-600 mb-6 animate-bounce">
                            <i class="ph-duotone ph-magnifying-glass text-5xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2 font-heading">Aucun cours trouvé</h3>
                        <p class="text-slate-500 max-w-md mx-auto mb-8 font-medium">
                            Nous n'avons trouvé aucun cours correspondant à vos critères. Essayez de modifier vos filtres ou
                            votre recherche.
                        </p>
                        <a href="{{ route('courses') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-white/5 hover:bg-white/10 text-white rounded-xl font-bold transition-all border border-white/10">
                            <i class="ph-bold ph-arrow-counter-clockwise"></i>
                            Réinitialiser les filtres
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>