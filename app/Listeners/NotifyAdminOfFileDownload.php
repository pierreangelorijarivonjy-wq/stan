<?php

namespace App\Listeners;

use App\Events\FileDownloaded;
use App\Models\User;
use App\Notifications\AdminActivityNotification;
use Illuminate\Support\Facades\Notification;

class NotifyAdminOfFileDownload
{
    /**
     * Handle the event.
     */
    public function handle(FileDownloaded $event): void
    {
        // Get all admin users
        $admins = User::role('admin')->get();

        $activityData = [
            'user_id' => $event->user->id,
            'user_name' => $event->user->name,
            'user_email' => $event->user->email,
            'file_type' => $event->fileType,
            'file_name' => $event->fileName,
            'context' => $event->context,
            'message' => "{$event->user->name} a téléchargé : {$event->fileName}",
        ];

        Notification::send($admins, new AdminActivityNotification('file_download', $activityData));
    }
}
