<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\User;
use App\Notifications\AdminActivityNotification;
use Illuminate\Support\Facades\Notification;

class NotifyAdminOfUserRegistration
{
    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        // Get all admin users
        $admins = User::role('admin')->get();

        $activityData = [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'user_email' => $event->user->email,
            'user_role' => $event->user->role,
            'message' => "Nouvel utilisateur inscrit : {$event->user->name} ({$event->user->role})",
        ];

        Notification::send($admins, new AdminActivityNotification('user_registration', $activityData));
    }
}
