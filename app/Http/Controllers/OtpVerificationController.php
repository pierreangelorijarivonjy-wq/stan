<?php

namespace App\Http\Controllers;

use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpVerificationController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Afficher le formulaire de vérification OTP
     */
    public function show(Request $request)
    {
        $user = Auth::user();

        // Si pas d'utilisateur connecté, chercher via la session de login
        if (!$user) {
            $userId = $request->session()->get('login.id');
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Veuillez vous connecter d\'abord.');
            }
            $user = \App\Models\User::find($userId);
            if (!$user) {
                return redirect()->route('login')->with('error', 'Utilisateur introuvable.');
            }
        }

        // Si déjà vérifié, rediriger vers dashboard
        if ($user->email_verified_via_otp && Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Si pas de code OTP, en générer un nouveau
        if (empty($user->otp_code) || $this->otpService->isOtpExpired($user)) {
            $this->otpService->sendOtp($user);
        }

        $timeRemaining = $this->otpService->getTimeRemaining($user);

        return view('auth.verify-otp', compact('timeRemaining'));
    }

    /**
     * Vérifier le code OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Le code de vérification est requis.',
            'otp.size' => 'Le code doit contenir exactement 6 chiffres.',
        ]);

        $user = Auth::user();

        // Si pas d'utilisateur connecté, chercher via la session de login
        if (!$user) {
            $userId = $request->session()->get('login.id');
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Session expirée. Veuillez vous reconnecter.');
            }
            $user = \App\Models\User::find($userId);
        }

        $otpCode = $request->input('otp');

        if ($this->otpService->verifyOtp($user, $otpCode)) {
            // Connecter l'utilisateur maintenant que l'OTP est validé
            Auth::login($user);
            $request->session()->regenerate();
            $request->session()->forget('login.id');

            // Mettre à jour la dernière connexion
            $user->update(['last_login_at' => now()]);

            // OTP valide - créer un log d'audit
            if (class_exists('App\Models\AuditLog')) {
                \App\Models\AuditLog::create([
                    'user_id' => $user->id,
                    'event' => 'login',
                    'auditable_type' => get_class($user),
                    'auditable_id' => $user->id,
                    'description' => 'Connexion réussie après vérification OTP',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            return redirect()->route('dashboard')
                ->with('success', 'Email vérifié avec succès ! Bienvenue sur EduPass.');
        }

        // OTP invalide ou expiré
        if ($this->otpService->isOtpExpired($user)) {
            return back()->withErrors([
                'otp' => 'Le code de vérification a expiré. Un nouveau code vous a été envoyé.',
            ])->with('otp_expired', true);
        }

        return back()->withErrors([
            'otp' => 'Code de vérification incorrect. Veuillez réessayer.',
        ]);
    }

    /**
     * Renvoyer un nouveau code OTP
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            $userId = $request->session()->get('login.id');
            if (!$userId) {
                return redirect()->route('login');
            }
            $user = \App\Models\User::find($userId);
        }

        if ($this->otpService->sendOtp($user)) {
            return back()->with('success', 'Un nouveau code de vérification a été envoyé à votre email.');
        }

        return back()->withErrors([
            'email' => 'Erreur lors de l\'envoi du code. Veuillez réessayer.',
        ]);
    }
}
