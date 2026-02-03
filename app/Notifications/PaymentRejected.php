<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentRejected extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Payment $payment,
        public string $reason = 'Paiement non valide'
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
        return [
            'type' => 'payment_rejected',
            'title' => 'Paiement refusé',
            'message' => "Votre paiement de " . number_format($this->payment->amount, 0, ',', ' ') . " Ar a été refusé. Motif : " . $this->reason,
            'icon' => '❌',
            'color' => 'red',
            'action_url' => route('payments'),
            'action_text' => 'Réessayer',
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
        ];
    }
}
