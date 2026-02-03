<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.global', function ($user) {
    return $user != null;
});

Broadcast::channel('chat.conversation.{id}', function ($user, $id) {
    return \App\Models\Conversation::find($id)->hasParticipant($user->id);
});
