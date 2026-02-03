<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Models\User;
use App\Notifications\AdminActivityNotification;
use Illuminate\Support\Facades\Notification;

class NotifyAdminOfUserLogin
{
    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        // Get all admin users
        $admins = User::role('admin')->get();

        // Don't notify if the logged-in user is an admin
        if ($event->user->hasRole('admin')) {
            return;
        }

        $activityData = [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'user_email' => $event->user->email,
            'user_role' => $event->user->role,
            'login_method' => $event->loginMethod,
            'message' => "{$event->user->name} ({$event->user->role}) s'est connecté",
        ];

        Notification::send($admins, new AdminActivityNotification('user_login', $activityData));
    }
}
