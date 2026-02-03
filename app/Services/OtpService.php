<?php

namespace App\Services;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpService
{
    /**
     * Générer un code OTP à 6 chiffres
     */
    public function generateOtp(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Créer et envoyer un OTP pour un utilisateur
     */
    public function sendOtp(User $user): bool
    {
        // Générer le code OTP
        $otpCode = $this->generateOtp();

        // Sauvegarder le code et l'expiration (3 minutes)
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => Carbon::now()->addMinutes(3),
        ]);

        // Envoyer l'email
        try {
            Mail::to($user->email)->send(new OtpMail($user, $otpCode));
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur envoi OTP: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier un code OTP
     */
    public function verifyOtp(User $user, string $code): bool
    {
        // Vérifier si le code existe
        if (empty($user->otp_code)) {
            return false;
        }

        // Vérifier si le code est expiré
        if ($this->isOtpExpired($user)) {
            return false;
        }

        // Vérifier si le code correspond
        if ($user->otp_code !== $code) {
            return false;
        }

        // Code valide - marquer comme vérifié et nettoyer
        $user->update([
            'email_verified_via_otp' => true,
            'email_verified_at' => Carbon::now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        return true;
    }

    /**
     * Vérifier si l'OTP est expiré
     */
    public function isOtpExpired(User $user): bool
    {
        if (empty($user->otp_expires_at)) {
            return true;
        }

        return Carbon::now()->isAfter($user->otp_expires_at);
    }

    /**
     * Obtenir le temps restant avant expiration (en secondes)
     */
    public function getTimeRemaining(User $user): int
    {
        if (empty($user->otp_expires_at)) {
            return 0;
        }

        $remaining = Carbon::now()->diffInSeconds($user->otp_expires_at, false);
        return max(0, $remaining);
    }
}
