@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto h-[calc(100vh-12rem)] flex flex-col">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-heading font-black text-white tracking-tight">Messagerie</h1>
                <p class="text-slate-400 text-sm">Discutez avec l'administration d'EduPass-MG</p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-xl">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-emerald-500 text-xs font-bold uppercase tracking-wider">Support en ligne</span>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="flex-1 glass rounded-[2.5rem] overflow-hidden flex flex-col border-white/10 shadow-2xl">
            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar bg-white/5" id="chat-messages">
                @if($messages->isEmpty())
                    <div class="h-full flex flex-col items-center justify-center text-center p-8">
                        <div
                            class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-4 border border-white/10">
                            <i class="fas fa-comments text-3xl text-slate-500"></i>
                        </div>
                        <h3 class="text-white font-bold text-lg mb-2">Aucun message pour le moment</h3>
                        <p class="text-slate-400 text-sm max-w-xs">
                            Posez votre première question à l'administration ci-dessous. Nous vous répondrons dans les plus
                            brefs délais.
                        </p>
                    </div>
                @else
                    @foreach($messages as $message)
                        <div class="flex {{ $message->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="max-w-[80%] flex flex-col {{ $message->sender_id == Auth::id() ? 'items-end' : 'items-start' }}">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                        {{ $message->sender_id == Auth::id() ? 'Vous' : 'Administration' }}
                                    </span>
                                    <span class="text-[10px] text-slate-600">
                                        {{ $message->created_at->format('H:i') }}
                                    </span>
                                </div>
                                <div
                                    class="px-5 py-3 rounded-2xl text-sm leading-relaxed {{ $message->sender_id == Auth::id() ? 'bg-premium-cyan text-white rounded-tr-none shadow-lg shadow-premium-cyan/20' : 'bg-white/10 text-slate-200 border border-white/10 rounded-tl-none' }}">
                                    {{ $message->content }}
                                </div>
                                @if($message->sender_id == Auth::id() && $message->read_at)
                                    <span class="text-[10px] text-emerald-500 mt-1 flex items-center gap-1">
                                        <i class="fas fa-check-double"></i> Lu
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Input Area -->
            <div class="p-6 bg-white/5 border-t border-white/10">
                <form action="{{ route('messages.store') }}" method="POST" class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-1 relative">
                        <textarea name="content" rows="1" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-white placeholder-slate-500 focus:ring-2 focus:ring-premium-cyan focus:border-transparent outline-none transition-all resize-none custom-scrollbar"
                            placeholder="Écrivez votre message ici..."
                            oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                    </div>
                    <button type="submit"
                        class="w-14 h-14 bg-premium-cyan hover:bg-premium-cyan/80 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-premium-cyan/20 transition-all hover:scale-105 active:scale-95 shrink-0">
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