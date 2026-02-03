<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileDownloaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $fileType;
    public string $fileName;
    public ?string $context;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $fileType, string $fileName, ?string $context = null)
    {
        $this->user = $user;
        $this->fileType = $fileType;
        $this->fileName = $fileName;
        $this->context = $context;
    }
}
