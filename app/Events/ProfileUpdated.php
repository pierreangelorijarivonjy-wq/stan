<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfileUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public array $updatedFields;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, array $updatedFields = [])
    {
        $this->user = $user;
        $this->updatedFields = $updatedFields;
    }
}
