<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    /**
     * List all conversations for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::whereHas('participants', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->whereNull('left_at');
        })
            ->with(['lastMessage', 'participants.user'])
            ->orderByDesc('last_message_at')
            ->get()
            ->map(function ($conversation) use ($user) {
                // Calculate unread count
                $conversation->unread_count = $conversation->getUnreadCount($user->id);

                // Format for frontend
                return [
                    'id' => $conversation->id,
                    'type' => $conversation->type,
                    'name' => $this->getConversationName($conversation, $user),
                    'avatar' => $this->getConversationAvatar($conversation, $user),
                    'last_message' => $conversation->lastMessage ? [
                        'content' => $conversation->lastMessage->content,
                        'created_at' => $conversation->lastMessage->created_at,
                        'sender_name' => $conversation->lastMessage->sender->name,
                    ] : null,
                    'unread_count' => $conversation->unread_count,
                    'updated_at' => $conversation->updated_at,
                    'participants' => $conversation->participants->map(function ($p) {
                    return [
                        'id' => $p->user->id,
                        'name' => $p->user->name,
                        'avatar_url' => $p->user->avatar_url,
                        'is_admin' => $p->is_admin,
                    ];
                }),
                    'is_admin' => $conversation->participants->where('user_id', $user->id)->first()->is_admin ?? false,
                ];
            });

        return response()->json($conversations);
    }

    /**
     * Create a new conversation (private or group).
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:private,group',
            'participants' => 'required|array|min:1',
            'participants.*' => 'exists:users,id',
            'name' => 'required_if:type,group|nullable|string|max:255',
        ]);

        $user = Auth::user();
        $participantIds = array_unique($request->participants);

        // For private conversations, check if one already exists
        if ($request->type === 'private' && count($participantIds) === 1) {
            $otherUserId = $participantIds[0];
            $existingConversation = Conversation::where('type', 'private')
                ->whereHas('participants', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->whereHas('participants', function ($q) use ($otherUserId) {
                    $q->where('user_id', $otherUserId);
                })
                ->first();

            if ($existingConversation) {
                return response()->json($existingConversation);
            }
        }

        DB::beginTransaction();
        try {
            $conversation = Conversation::create([
                'type' => $request->type,
                'name' => $request->type === 'group' ? $request->name : null,
                'created_by' => $user->id,
                'last_message_at' => now(),
            ]);

            // Add current user (Admin)
            ConversationParticipant::create([
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'is_admin' => true,
            ]);

            // Add other participants
            foreach ($participantIds as $participantId) {
                if ($participantId != $user->id) {
                    ConversationParticipant::create([
                        'conversation_id' => $conversation->id,
                        'user_id' => $participantId,
                        'is_admin' => false,
                    ]);
                }
            }

            DB::commit();

            // Reload to get relationships
            $conversation->load(['lastMessage', 'participants.user']);

            // Format response to match index structure
            $formattedConversation = [
                'id' => $conversation->id,
                'type' => $conversation->type,
                'name' => $this->getConversationName($conversation, $user),
                'avatar' => $this->getConversationAvatar($conversation, $user),
                'last_message' => null,
                'unread_count' => 0,
                'updated_at' => $conversation->updated_at,
                'participants' => $conversation->participants->map(function ($p) {
                    return [
                        'id' => $p->user->id,
                        'name' => $p->user->name,
                        'avatar_url' => $p->user->avatar_url,
                        'is_admin' => $p->is_admin,
                    ];
                }),
                'is_admin' => true,
            ];

            return response()->json($formattedConversation, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create conversation: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show a specific conversation with messages.
     */
    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $messages = $conversation->messages()
            ->with(['sender', 'reactions', 'replyTo'])
            ->latest()
            ->paginate(50);

        return response()->json($messages);
    }

    /**
     * Search for users to start a conversation with.
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q');
        $user = Auth::user();

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', $user->id)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->take(10)
            ->get(['id', 'name', 'email', 'avatar_url']); // Adjust fields as needed

        return response()->json($users);
    }

    /**
     * Add a participant to a group conversation.
     */
    public function addParticipant(Request $request, Conversation $conversation)
    {
        $this->authorize('update', $conversation); // Ensure user is admin of the group

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        if ($conversation->type !== 'group') {
            return response()->json(['error' => 'Cannot add participants to private conversation'], 400);
        }

        // Check if already a participant
        $existing = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existing) {
            if ($existing->left_at) {
                $existing->update(['left_at' => null]); // Re-join
            }
        } else {
            ConversationParticipant::create([
                'conversation_id' => $conversation->id,
                'user_id' => $request->user_id,
                'is_admin' => false,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove a participant from a group conversation.
     */
    public function removeParticipant(Conversation $conversation, User $user)
    {
        $this->authorize('update', $conversation); // Ensure user is admin of the group

        if ($conversation->type !== 'group') {
            return response()->json(['error' => 'Cannot remove participants from private conversation'], 400);
        }

        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->first();

        if ($participant) {
            $participant->update(['left_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Leave a group conversation.
     */
    public function leaveConversation(Conversation $conversation)
    {
        $user = Auth::user();

        if ($conversation->type !== 'group') {
            return response()->json(['error' => 'Cannot leave private conversation'], 400);
        }

        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->first();

        if ($participant) {
            $participant->update(['left_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Helper to get conversation name.
     */
    private function getConversationName($conversation, $currentUser)
    {
        if ($conversation->type === 'group') {
            return $conversation->name;
        }

        $otherParticipant = $conversation->participants
            ->where('user_id', '!=', $currentUser->id)
            ->first();

        return $otherParticipant ? $otherParticipant->user->name : 'Unknown User';
    }

    /**
     * Helper to get conversation avatar.
     */
    private function getConversationAvatar($conversation, $currentUser)
    {
        if ($conversation->type === 'group') {
            return null; // TODO: Group avatar
        }

        $otherParticipant = $conversation->participants
            ->where('user_id', '!=', $currentUser->id)
            ->first();

        return $otherParticipant ? $otherParticipant->user->avatar_url : null; // Assuming avatar_url exists
    }
    /**
     * Mark conversation as read.
     */
    public function markAsRead(Conversation $conversation)
    {
        $user = Auth::user();

        // Update participant last_read_at
        $participant = ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $user->id)
            ->first();

        if ($participant) {
            $participant->update(['last_read_at' => now()]);

            // Broadcast event
            broadcast(new \App\Events\MessageRead($conversation->id, $user->id))->toOthers();
        }

        return response()->json(['success' => true]);
    }
}
