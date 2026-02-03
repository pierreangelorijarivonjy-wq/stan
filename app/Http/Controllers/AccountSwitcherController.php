<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountSwitcherController extends Controller
{
    public function index()
    {
        $accounts = [
            'admin' => User::where('email', 'admin@edupass.mg')->first(),
            'comptable' => User::where('email', 'comptable@edupass.mg')->first(),
            'scolarite' => User::where('email', 'scolarite@edupass.mg')->first(),
            'students' => User::role('student')
                ->where('email', 'not like', '%test%')
                ->where('email', 'not like', '%etudiant%')
                ->where('name', 'not like', '%Test%')
                ->take(8)
                ->get(),
        ];

        return view('account-switcher', compact('accounts'));
    }

    public function switch(Request $request)
    {
        // Support both email and user_id for compatibility
        $user = null;

        if ($request->has('user_id')) {
            $user = User::find($request->user_id);
        } elseif ($request->has('email')) {
            $user = User::where('email', $request->email)->first();
        }

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Compte non trouvé'], 404);
            }
            return back()->with('error', 'Compte non trouvé');
        }

        try {
            \Illuminate\Support\Facades\Log::info('Switching account', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
            ]);

            $isStaff = $user->hasAnyRole(['admin', 'comptable', 'scolarite', 'controleur']);

            \Illuminate\Support\Facades\Log::info('Is staff check', ['is_staff' => $isStaff]);

            if (!$isStaff) {
                // Pour les étudiants, on utilise le nouveau système OTP

                // Connecter l'utilisateur (sans session complète encore, car middleware OTP bloquera)
                // Note: Le switcher connecte généralement l'utilisateur directement, 
                // mais ici on veut forcer la vérification OTP.

                // On stocke l'ID pour le switch
                // Mais attendez, le switch connecte l'utilisateur. 
                // Si on connecte l'utilisateur, le middleware EnsureOtpVerified interviendra.

                Auth::login($user);
                $request->session()->regenerate();

                // Envoyer l'OTP via le service
                $otpService = app(\App\Services\OtpService::class);
                $otpService->sendOtp($user);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Code de vérification envoyé à {$user->email}",
                        'redirect' => route('otp.show')
                    ]);
                }

                return redirect()->route('otp.show')
                    ->with('success', "Compte changé. Un code de vérification a été envoyé à {$user->email}");
            }

            // Pour le staff, on demande le mot de passe
            $request->session()->put('auth.switch_user_id', $user->id);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Veuillez entrer le mot de passe pour accéder au compte {$user->name}",
                    'redirect' => route('account.switch.password')
                ]);
            }

            return redirect()->route('account.switch.password')->with('info', "Veuillez entrer le mot de passe pour accéder au compte {$user->name}");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur Switcher : ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Erreur technique lors du changement de compte.'], 500);
            }

            return back()->with('error', 'Erreur technique lors du changement de compte.');
        }
    }

    public function showPasswordForm(Request $request)
    {
        $userId = $request->session()->get('auth.switch_user_id');
        if (!$userId) {
            return redirect()->route('account.switcher')->with('error', 'Session expirée.');
        }

        $user = User::findOrFail($userId);
        return view('auth.switch-password', compact('user'));
    }

    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $userId = $request->session()->get('auth.switch_user_id');
        if (!$userId) {
            return redirect()->route('account.switcher')->with('error', 'Session expirée.');
        }

        $user = User::findOrFail($userId);

        if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        // Connexion réussie
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('auth.two_factor_verified', true);
        $request->session()->forget('auth.switch_user_id');

        // Dispatch login event
        event(new \App\Events\UserLoggedIn($user, 'account_switch'));

        return redirect()->intended(route('dashboard'))->with('success', "Connecté en tant que {$user->name}");
    }
}

