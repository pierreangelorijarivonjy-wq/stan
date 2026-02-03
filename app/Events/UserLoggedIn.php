<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $loginMethod;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $loginMethod = 'standard')
    {
        $this->user = $user;
        $this->loginMethod = $loginMethod;
    }
}
