<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminActivityNotification extends Notification
{
    use Queueable;

    protected string $activityType;
    protected array $activityData;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $activityType, array $activityData)
    {
        $this->activityType = $activityType;
        $this->activityData = $activityData;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $data = $this->activityData;
        $type = $this->activityType;

        // Defaults
        $icon = '📢';
        $color = 'indigo';
        $title = 'Nouvelle Activité';
        $message = $data['message'] ?? 'Une action a été effectuée.';

        // Map types to icons/colors
        switch ($type) {
            case 'user_login':
                $icon = '🔓';
                $color = 'blue';
                $title = 'Connexion Utilisateur';
                break;
            case 'user_register':
                $icon = '👤';
                $color = 'green';
                $title = 'Nouvelle Inscription';
                break;
            case 'user_logout':
                $icon = '🔒';
                $color = 'gray';
                $title = 'Déconnexion';
                break;
            case 'payment_completed':
                $icon = '💰';
                $color = 'green';
                $title = 'Paiement Reçu';
                break;
            case 'payment_failed':
                $icon = '❌';
                $color = 'red';
                $title = 'Échec Paiement';
                break;
            case 'course_accessed':
                $icon = '📚';
                $color = 'purple';
                $title = 'Accès Cours';
                break;
            case 'file_downloaded':
                $icon = '📥';
                $color = 'orange';
                $title = 'Téléchargement';
                break;
            case 'profile_updated':
                $icon = '✏️';
                $color = 'yellow';
                $title = 'Profil Modifié';
                break;
        }

        return array_merge([
            'type' => $type,
            'icon' => $icon,
            'color' => $color,
            'title' => $title,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
        ], $data);
    }

    /**
     * Get the notification's database representation.
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
