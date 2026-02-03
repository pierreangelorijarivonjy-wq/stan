<?php

namespace App\Listeners;

use App\Events\PaymentCompleted;
use App\Events\PaymentFailed;
use App\Models\User;
use App\Notifications\AdminActivityNotification;
use Illuminate\Support\Facades\Notification;

class NotifyAdminOfPayment
{
    /**
     * Handle payment completed event.
     */
    public function handleCompleted(PaymentCompleted $event): void
    {
        // Get all admin users
        $admins = User::role('admin')->get();

        $payment = $event->payment;
        $user = $payment->user;

        $activityData = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'provider' => $payment->provider,
            'transaction_id' => $payment->transaction_id,
            'message' => "Paiement réussi : {$user->name} - {$payment->amount} Ar via {$payment->provider}",
        ];

        Notification::send($admins, new AdminActivityNotification('payment_success', $activityData));
    }

    /**
     * Handle payment failed event.
     */
    public function handleFailed(PaymentFailed $event): void
    {
        // Get all admin users
        $admins = User::role('admin')->get();

        $payment = $event->payment;
        $user = $payment->user;

        $activityData = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'provider' => $payment->provider,
            'transaction_id' => $payment->transaction_id,
            'reason' => $event->reason,
            'message' => "Paiement échoué : {$user->name} - {$payment->amount} Ar via {$payment->provider}",
        ];

        Notification::send($admins, new AdminActivityNotification('payment_failed', $activityData));
    }
}

