<?php

namespace App\Listeners;

use App\Events\CourseAccessed;
use App\Models\User;
use App\Notifications\AdminActivityNotification;
use Illuminate\Support\Facades\Notification;

class NotifyAdminOfCourseAccess
{
    /**
     * Handle the event.
     */
    public function handle(CourseAccessed $event): void
    {
        // Get all admin users
        $admins = User::role('admin')->get();

        $activityData = [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'user_email' => $event->user->email,
            'course_slug' => $event->courseSlug,
            'course_title' => $event->courseTitle,
            'lesson_id' => $event->lessonId,
            'message' => "{$event->user->name} a accédé au cours : {$event->courseTitle}",
        ];

        Notification::send($admins, new AdminActivityNotification('course_access', $activityData));
    }
}
