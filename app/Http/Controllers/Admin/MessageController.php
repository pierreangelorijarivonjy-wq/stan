<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        // Get all unique students who have messages
        $conversations = User::role('student')
            ->whereHas('messages')
            ->withCount([
                'messages as unread_count' => function ($query) {
                    $query->whereNull('read_at')->where('sender_id', '!=', Auth::id());
                }
            ])
            ->with([
                'messages' => function ($query) {
                    $query->latest()->limit(1);
                }
            ])
            ->get()
            ->sortByDesc(function ($user) {
                return $user->messages->first()->created_at ?? 0;
            });

        return view('admin.messages.index', compact('conversations'));
    }

    public function show($userId)
    {
        $student = User::findOrFail($userId);

        $messages = Message::where('user_id', $userId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        Message::where('user_id', $userId)
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('admin.messages.show', compact('student', 'messages'));
    }

    public function store(Request $request, $userId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Message::create([
            'user_id' => $userId,
            'sender_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Réponse envoyée.');
    }
}
