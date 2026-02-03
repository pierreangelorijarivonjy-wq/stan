<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = \App\Models\User::where('email', $request->email)->first();

        // Vérifier si le compte est actif
        if ($user->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            return back()->withErrors([
                'email' => 'Votre compte est en attente de validation ou a été désactivé.',
            ]);
        }

        // Enforce OTP for all users
        try {
            // Reset OTP verification status for this session
            $user->update(['email_verified_via_otp' => false]);
            
            $request->session()->put('login.id', $user->id);

            // Send OTP via service
            $otpService = app(\App\Services\OtpService::class);
            $otpService->sendOtp($user);

            // Dispatch login event (attempt)
            event(new \App\Events\UserLoggedIn($user, 'otp_sent'));

            // Redirect to OTP verification page
            return redirect()->route('otp.show')
                ->with('success', 'Un code de vérification a été envoyé à votre email.');
        } catch (\Exception $e) {
            Auth::logout();
            $request->session()->invalidate();

            \Illuminate\Support\Facades\Log::error('Erreur d\'envoi OTP : ' . $e->getMessage());

            return back()->withErrors([
                'email' => 'Erreur technique lors de l\'envoi du code de sécurité. Veuillez vérifier la configuration SMTP.',
            ]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Dispatch logout event
        if ($user) {
            if (class_exists('App\Models\AuditLog')) {
                \App\Models\AuditLog::create([
                    'user_id' => $user->id,
                    'event' => 'logout',
                    'auditable_type' => get_class($user),
                    'auditable_id' => $user->id,
                    'description' => 'Déconnexion de l\'utilisateur',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
            event(new \App\Events\UserLoggedOut($user));
        }

        return redirect('/');
    }
}
