<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SecurityAlert extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $type, // 'login', 'otp', 'password_change'
        public string $message,
        public ?string $location = null,
        public ?string $ip = null
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $icon = match ($this->type) {
            'login' => '🔓',
            'otp' => '🔑',
            'password_change' => '🛡️',
            default => '⚠️'
        };

        return [
            'type' => 'security',
            'title' => 'Alerte de Sécurité',
            'message' => $this->message,
            'icon' => $icon,
            'color' => 'orange',
            'action_url' => null,
            'action_text' => null,
            'metadata' => [
                'ip' => $this->ip,
                'location' => $this->location,
                'time' => now()->toDateTimeString()
            ]
        ];
    }
}
