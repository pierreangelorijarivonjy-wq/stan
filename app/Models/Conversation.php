<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'name',
        'description',
        'created_by',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    public function activeParticipants()
    {
        return $this->participants()->whereNull('left_at');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    // Helper methods
    public function isPrivate()
    {
        return $this->type === 'private';
    }

    public function isGroup()
    {
        return $this->type === 'group';
    }

    public function hasParticipant($userId)
    {
        return $this->activeParticipants()->where('user_id', $userId)->exists();
    }

    public function getUnreadCount($userId)
    {
        $participant = $this->participants()->where('user_id', $userId)->first();
        if (!$participant)
            return 0;

        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where(function ($query) use ($participant) {
                $query->where('created_at', '>', $participant->last_read_at)
                    ->orWhereNull($participant->last_read_at);
            })
            ->count();
    }
}
