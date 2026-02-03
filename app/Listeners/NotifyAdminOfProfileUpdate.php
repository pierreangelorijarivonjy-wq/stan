<?php

namespace App\Listeners;

use App\Events\ProfileUpdated;
use App\Models\User;
use App\Notifications\AdminActivityNotification;
use Illuminate\Support\Facades\Notification;

class NotifyAdminOfProfileUpdate
{
    /**
     * Handle the event.
     */
    public function handle(ProfileUpdated $event): void
    {
        // Get all admin users
        $admins = User::role('admin')->get();

        $updatedFieldsList = !empty($event->updatedFields)
            ? implode(', ', $event->updatedFields)
            : 'profil';

        $activityData = [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'user_email' => $event->user->email,
            'updated_fields' => $event->updatedFields,
            'message' => "{$event->user->name} a modifié son profil : {$updatedFieldsList}",
        ];

        Notification::send($admins, new AdminActivityNotification('profile_update', $activityData));
    }
}
