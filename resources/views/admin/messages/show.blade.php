@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto h-[calc(100vh-12rem)] flex flex-col">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.messages.index') }}"
                    class="w-10 h-10 glass rounded-xl flex items-center justify-center text-slate-400 hover:text-white transition-all hover:bg-white/10">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-heading font-black text-white tracking-tight">{{ $student->name }}</h1>
                    <p class="text-slate-400 text-xs uppercase tracking-widest font-bold">Conversation Étudiant</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-white text-sm font-bold">{{ $student->email }}</p>
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest">Matricule:
                        {{ $student->matricule ?? 'N/A' }}</p>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-black shadow-lg">
                    {{ substr($student->name, 0, 1) }}
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="flex-1 glass rounded-[2.5rem] overflow-hidden flex flex-col border-white/10 shadow-2xl">
            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar bg-white/5" id="chat-messages">
                @foreach($messages as $message)
                    <div class="flex {{ $message->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-[80%] flex flex-col {{ $message->sender_id == Auth::id() ? 'items-end' : 'items-start' }}">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                    {{ $message->sender_id == Auth::id() ? 'Vous (Admin)' : $student->name }}
                                </span>
                                <span class="text-[10px] text-slate-600">
                                    {{ $message->created_at->format('d M, H:i') }}
                                </span>
                            </div>
                            <div
                                class="px-5 py-3 rounded-2xl text-sm leading-relaxed {{ $message->sender_id == Auth::id() ? 'bg-premium-purple text-white rounded-tr-none shadow-lg shadow-premium-purple/20' : 'bg-white/10 text-slate-200 border border-white/10 rounded-tl-none' }}">
                                {{ $message->content }}
                            </div>
                            @if($message->sender_id == Auth::id() && $message->read_at)
                                <span class="text-[10px] text-emerald-500 mt-1 flex items-center gap-1">
                                    <i class="fas fa-check-double"></i> Lu par l'étudiant
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input Area -->
            <div class="p-6 bg-white/5 border-t border-white/10">
                <form action="{{ route('admin.messages.store', $student->id) }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-1 relative">
                        <textarea name="content" rows="1" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-slate-500 focus:ring-2 focus:ring-premium-purple focus:border-transparent outline-none transition-all resize-none custom-scrollbar"
                            placeholder="Répondre à l'étudiant..."
                            oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                    </div>
                    <button type="submit"
                        class="w-14 h-14 bg-premium-purple hover:bg-premium-purple/80 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-premium-purple/20 transition-all hover:scale-105 active:scale-95 shrink-0">
                        <i class="fas fa-paper-plane text-xl"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Scroll to bottom of chat
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
@endsection