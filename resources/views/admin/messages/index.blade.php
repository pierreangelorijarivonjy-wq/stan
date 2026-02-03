@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-heading font-black text-white tracking-tight">Gestion des Messages</h1>
        <p class="text-slate-400">Répondez aux questions des étudiants et gérez l'assistance.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Conversations List -->
        <div class="lg:col-span-3">
            <div class="glass rounded-[2.5rem] border-white/10 overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-white/10 bg-white/5 flex justify-between items-center">
                    <h2 class="text-white font-bold flex items-center gap-2">
                        <i class="fas fa-inbox text-premium-cyan"></i>
                        Conversations actives
                    </h2>
                    <span class="bg-white/10 text-slate-400 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ $conversations->count() }} Étudiants
                    </span>
                </div>

                <div class="divide-y divide-white/5">
                    @if($conversations->isEmpty())
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/10">
                                <i class="fas fa-comment-slash text-2xl text-slate-600"></i>
                            </div>
                            <p class="text-slate-500 font-medium">Aucune conversation en cours.</p>
                        </div>
                    @else
                        @foreach($conversations as $student)
                            <a href="{{ route('admin.messages.show', $student->id) }}" class="block p-6 hover:bg-white/5 transition-all group relative">
                                <div class="flex items-center gap-4">
                                    <!-- Avatar -->
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-black shadow-lg group-hover:scale-110 transition-transform">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start mb-1">
                                            <h3 class="text-white font-bold group-hover:text-premium-cyan transition-colors truncate">
                                                {{ $student->name }}
                                            </h3>
                                            <span class="text-[10px] text-slate-500 font-medium uppercase">
                                                {{ $student->messages->first()->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-slate-400 truncate">
                                            @if($student->messages->first()->sender_id == Auth::id())
                                                <span class="text-premium-cyan font-bold">Vous:</span>
                                            @endif
                                            {{ $student->messages->first()->content }}
                                        </p>
                                    </div>

                                    <!-- Status -->
                                    <div class="flex flex-col items-end gap-2">
                                        @if($student->unread_count > 0)
                                            <span class="bg-premium-cyan text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-lg shadow-premium-cyan/20 animate-bounce">
                                                {{ $student->unread_count }}
                                            </span>
                                        @endif
                                        <i class="fas fa-chevron-right text-slate-600 group-hover:translate-x-1 transition-transform"></i>
                                    </div>
                                </div>
                                
                                @if($student->unread_count > 0)
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-premium-cyan"></div>
                                @endif
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
