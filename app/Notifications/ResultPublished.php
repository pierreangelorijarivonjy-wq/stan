<?php

namespace App\Notifications;

use App\Models\Result;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResultPublished extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Result $result
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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $session = $this->result->examSession;
        return (new MailMessage)
            ->subject('Résultat d\'examen disponible - EduPass-MG')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre résultat pour la matière ' . $this->result->subject . ' (Session : ' . $session->type . ') est maintenant disponible.')
            ->line('Note obtenue : ' . $this->result->grade . ' / 20')
            ->action('Consulter mes résultats', url('/dashboard'))
            ->line('Félicitations pour vos efforts !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'result',
            'title' => 'Nouveau résultat publié',
            'message' => "Votre note en {$this->result->subject} ({$this->result->grade}/20) est disponible.",
            'icon' => '🎓',
            'color' => 'emerald',
            'action_url' => route('dashboard'),
            'result_id' => $this->result->id,
            'subject' => $this->result->subject,
            'grade' => $this->result->grade,
        ];
    }
}
