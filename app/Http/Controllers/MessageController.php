<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all messages for this user (student)
        $messages = Message::where('user_id', $user->id)
            ->with('sender')
            ->whereDoesntHave('hiddenBy', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages as read
        Message::where('user_id', $user->id)
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($messages);
        }

        return view('messages.index', compact('messages'));
    }

    public function getMessages()
    {
        $user = Auth::user();
        if (!$user)
            return response()->json([]);

        // Fetch all messages for global student chat (paginated)
        $messages = Message::with(['sender', 'reactions', 'replyTo.sender'])
            ->whereDoesntHave('hiddenBy', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc') // Order by desc for pagination (newest first)
            ->paginate(50);

        // Transform collection
        $messages->getCollection()->transform(function ($message) use ($user) {
            $message->reactions_grouped = $message->reactions->groupBy('emoji')->map(function ($group) use ($user) {
                return [
                    'count' => $group->count(),
                    'reacted_by_me' => $group->contains('user_id', $user->id)
                ];
            });
            if ($message->is_deleted) {
                $message->content = 'Ce message a été supprimé';
            }
            return $message;
        });

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'nullable|string|max:1000',
            'attachment' => 'nullable|file|max:51200', // Max 50MB
            'conversation_id' => 'nullable|exists:conversations,id',
            'reply_to_message_id' => 'nullable|exists:messages,id',
        ]);

        if (!$request->content && !$request->hasFile('attachment')) {
            return response()->json(['error' => 'Le message ne peut pas être vide.'], 422);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Rate Limiting: 10 messages per minute
        $key = 'messages:send:' . $user->id;
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            return response()->json(['error' => "Trop de messages. Veuillez attendre $seconds secondes."], 429);
        }
        \Illuminate\Support\Facades\RateLimiter::hit($key);

        // Security: Check if user is participant of the conversation
        if ($request->conversation_id) {
            $conversation = \App\Models\Conversation::find($request->conversation_id);
            if (!$conversation || !$conversation->hasParticipant($user->id)) {
                return response()->json(['error' => 'Vous ne faites pas partie de cette conversation.'], 403);
            }
        }

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('chat-attachments', 'public');
            $attachmentPath = '/storage/' . $path;
            $mime = $file->getMimeType();

            if (str_starts_with($mime, 'image/')) {
                $attachmentType = 'image';
            } else if (str_starts_with($mime, 'audio/')) {
                $attachmentType = 'audio';
            } else {
                $attachmentType = 'file';
            }
        }

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'user_id' => null, // Global chat or conversation context
            'sender_id' => $user->id,
            'content' => $request->content ?? '',
            'reply_to_message_id' => $request->reply_to_message_id,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
        ]);

        // Update conversation last_message_at
        if ($request->conversation_id) {
            \App\Models\Conversation::where('id', $request->conversation_id)
                ->update(['last_message_at' => now()]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $message->load(['sender', 'replyTo.sender']);
            $message->reactions_grouped = [];

            broadcast(new \App\Events\MessageSent($message))->toOthers();

            return response()->json($message);
        }

        return redirect()->back()->with('success', 'Message envoyé avec succès.');
    }

    public function react(Request $request, Message $message)
    {
        $request->validate(['emoji' => 'required|string']);
        $user = Auth::user();
        $emoji = $request->emoji;

        $reaction = \App\Models\MessageReaction::where('user_id', $user->id)
            ->where('message_id', $message->id)
            ->where('emoji', $emoji)
            ->first();

        if ($reaction) {
            $reaction->delete();
            $reacted = false;
        } else {
            \App\Models\MessageReaction::create([
                'user_id' => $user->id,
                'message_id' => $message->id,
                'emoji' => $emoji,
            ]);
            $reacted = true;
        }

        // Return updated reactions
        $reactions = $message->reactions()->get()->groupBy('emoji')->map(function ($group) use ($user) {
            return [
                'count' => $group->count(),
                'reacted_by_me' => $group->contains('user_id', $user->id)
            ];
        });

        broadcast(new \App\Events\MessageReactionAdded($message->id, $reactions, $message->conversation_id))->toOthers();

        return response()->json([
            'reacted' => $reacted,
            'reactions' => $reactions
        ]);
    }

    public function update(Request $request, Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($message->created_at->diffInMinutes(now()) > 15) {
            return response()->json(['error' => 'Time limit exceeded'], 403);
        }

        $request->validate(['content' => 'required|string|max:1000']);

        $message->update([
            'content' => $request->content,
            'edited_at' => now(),
        ]);

        broadcast(new \App\Events\MessageUpdated($message))->toOthers();

        return response()->json($message);
    }

    public function destroy(Message $message)
    {
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->update(['is_deleted' => true]);

        broadcast(new \App\Events\MessageUpdated($message))->toOthers();

        return response()->json(['success' => true]);
    }

    public function hide(Message $message)
    {
        \App\Models\MessageHidden::firstOrCreate([
            'user_id' => Auth::id(),
            'message_id' => $message->id,
        ]);

        return response()->json(['success' => true]);
    }

    public function report(Request $request, Message $message)
    {
        $request->validate(['reason' => 'nullable|string|max:255']);

        \App\Models\MessageReport::firstOrCreate([
            'user_id' => Auth::id(),
            'message_id' => $message->id,
        ], [
            'reason' => $request->reason,
        ]);

        return response()->json(['success' => true]);
    }
}
