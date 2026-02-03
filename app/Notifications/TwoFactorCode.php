<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCode extends Notification
{
    protected $code;

    /**
     * Create a new notification instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Votre code de confirmation EduPass'))
            ->greeting(__('Bienvenue :name !', ['name' => $notifiable->name]))
            ->line(__('Merci de vous être inscrit sur EduPass-MG.'))
            ->line(__('Voici votre code de confirmation à 6 chiffres pour valider votre compte :'))
            ->line('**' . $this->code . '**')
            ->line(__('Ce code expirera dans 10 minutes.'))
            ->line(__('Entrez ce code sur la plateforme pour commencer.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'code' => $this->code,
        ];
    }
}
