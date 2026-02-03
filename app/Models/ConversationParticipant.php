<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationParticipant extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'joined_at',
        'left_at',
        'last_read_at',
        'is_muted',
        'is_admin',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'last_read_at' => 'datetime',
        'is_muted' => 'boolean',
        'is_admin' => 'boolean',
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

    // Helper methods
    public function isActive()
    {
        return is_null($this->left_at);
    }

    public function markAsRead()
    {
        $this->update(['last_read_at' => now()]);
    }
}
