<?php

namespace App\Listeners;

use App\Events\UserLoggedOut;
use App\Models\User;
use App\Notifications\AdminActivityNotification;
use Illuminate\Support\Facades\Notification;

class NotifyAdminOfUserLogout
{
    /**
     * Handle the event.
     */
    public function handle(UserLoggedOut $event): void
    {
        // Get all admin users
        $admins = User::role('admin')->get();

        // Don't notify if the logged-out user is an admin
        if ($event->user->hasRole('admin')) {
            return;
        }

        $activityData = [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'user_email' => $event->user->email,
            'user_role' => $event->user->role,
            'message' => "{$event->user->name} ({$event->user->role}) s'est déconnecté",
        ];

        Notification::send($admins, new AdminActivityNotification('user_logout', $activityData));
    }
}
