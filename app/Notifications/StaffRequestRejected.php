<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffRequestRejected extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre demande d\'accès Staff a été rejetée - EduPass')
            ->line('Désolé ' . $this->user->name . ', votre demande d\'accès en tant que staff a été rejetée par l\'administration.')
            ->line('Si vous pensez qu\'il s\'agit d\'une erreur, veuillez contacter le support.')
            ->line('Merci d\'utiliser EduPass !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Votre demande d\'accès Staff a été rejetée.',
            'type' => 'staff_rejected',
        ];
    }
}
