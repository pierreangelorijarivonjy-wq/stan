<?php

namespace App\Notifications;

use App\Models\Convocation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ConvocationReady extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Convocation $convocation
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
        $session = $this->convocation->examSession;

        return [
            'type' => 'convocation',
            'title' => 'Convocation disponible',
            'message' => "Votre convocation pour {$session->type} du {$session->date->format('d/m/Y')} est disponible.",
            'icon' => '📋',
            'color' => 'blue',
            'action_url' => route('convocations.download', $this->convocation),
            'action_text' => 'Télécharger',
            'convocation_id' => $this->convocation->id,
            'session_date' => $session->date->format('Y-m-d'),
        ];
    }
}

