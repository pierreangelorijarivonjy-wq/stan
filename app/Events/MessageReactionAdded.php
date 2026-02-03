<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReactionAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $reactions;
    public $conversationId;

    /**
     * Create a new event instance.
     */
    public function __construct($messageId, $reactions, $conversationId = null)
    {
        $this->messageId = $messageId;
        $this->reactions = $reactions;
        $this->conversationId = $conversationId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        if ($this->conversationId) {
            return [new PrivateChannel('chat.conversation.' . $this->conversationId)];
        }
        return [new PrivateChannel('chat.global')];
    }
}
