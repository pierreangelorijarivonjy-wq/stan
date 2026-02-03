<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseAccessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public string $courseSlug;
    public string $courseTitle;
    public ?int $lessonId;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $courseSlug, string $courseTitle, ?int $lessonId = null)
    {
        $this->user = $user;
        $this->courseSlug = $courseSlug;
        $this->courseTitle = $courseTitle;
        $this->lessonId = $lessonId;
    }
}
