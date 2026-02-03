<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffRegistrationRequested extends Notification
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
            ->subject('Nouvelle demande d\'inscription Staff - EduPass')
            ->line('Une nouvelle demande d\'inscription en tant que staff a été reçue.')
            ->line('Utilisateur : ' . $this->user->name)
            ->line('Email : ' . $this->user->email)
            ->line('Rôle demandé : ' . $this->user->requested_role)
            ->action('Gérer les demandes', url('/admin/staff-requests'))
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
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'requested_role' => $this->user->requested_role,
            'message' => 'Nouvelle demande d\'inscription Staff de ' . $this->user->name,
            'type' => 'staff_request',
        ];
    }
}
