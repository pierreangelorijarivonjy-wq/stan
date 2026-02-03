<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'sender_id',
        'reply_to_message_id',
        'content',
        'read_at',
        'edited_at',
        'delivered_at',
        'is_deleted',
        'attachment_path',
        'attachment_type',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'edited_at' => 'datetime',
        'delivered_at' => 'datetime',
        'is_deleted' => 'boolean',
    ];

    // Relationships
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_message_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to_message_id');
    }

    public function reactions()
    {
        return $this->hasMany(MessageReaction::class);
    }

    public function reads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function likes()
    {
        return $this->hasMany(MessageLike::class);
    }

    public function hiddenBy()
    {
        return $this->hasMany(MessageHidden::class);
    }

    // Helper methods
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function hasReaction($userId, $emoji = null)
    {
        $query = $this->reactions()->where('user_id', $userId);
        if ($emoji) {
            $query->where('emoji', $emoji);
        }
        return $query->exists();
    }

    public function isReadBy($userId)
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }

    public function markAsRead($userId)
    {
        return MessageRead::firstOrCreate([
            'message_id' => $this->id,
            'user_id' => $userId,
        ], [
            'read_at' => now(),
        ]);
    }

    public function isEdited()
    {
        return !is_null($this->edited_at);
    }
}
