<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageReaction extends Model
{
    protected $fillable = [
        'message_id',
        'user_id',
        'emoji',
    ];

    // Relationships
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Available emojis
    public static function availableEmojis()
    {
        return ['👍', '❤️', '😂', '😮', '😢', '👎'];
    }
}
