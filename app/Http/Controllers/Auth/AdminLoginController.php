<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AdminLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    /**
     * Display the admin login view.
     */
    public function create(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Handle an incoming admin authentication request.
     */
    public function store(AdminLoginRequest $request): RedirectResponse
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

        try {
            // Pour le personnel administratif, le mot de passe suffit (bypass OTP)
            Auth::login($user);

            // Marquer comme vérifié pour le middleware EnsureOtpVerified
            $user->update([
                'email_verified_via_otp' => true,
                'last_login_at' => now()
            ]);

            $request->session()->put('auth.two_factor_verified', true);
            $request->session()->regenerate();

            // Dispatch login event
            event(new \App\Events\UserLoggedIn($user, 'admin_login'));

            return redirect()->route('dashboard')
                ->with('success', 'Connexion réussie.');
        } catch (\Exception $e) {
            Auth::logout();
            $request->session()->invalidate();
            \Illuminate\Support\Facades\Log::error('Erreur de connexion (Admin/OTP) : ' . $e->getMessage());

            return back()->withErrors([
                'email' => 'Erreur technique lors de l\'envoi du code de sécurité.',
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
                    'description' => 'Déconnexion de l\'administrateur',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
            event(new \App\Events\UserLoggedOut($user));
        }

        return redirect('/admin/login');
    }
}
