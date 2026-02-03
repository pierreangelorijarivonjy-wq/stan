<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorChallengeController extends Controller
{
    protected $twoFactor;

    public function __construct(TwoFactorService $twoFactor)
    {
        $this->twoFactor = $twoFactor;
    }

    public function create(Request $request)
    {
        if (!$request->session()->has('auth.2fa_user_id') && !Auth::check()) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function store(Request $request)
    {
        $userId = $request->session()->get('auth.2fa_user_id') ?? Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::findOrFail($userId);
        $key = 'two-factor-login:' . $user->id;

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'code' => [__('Trop de tentatives. Veuillez réessayer dans :seconds secondes.', ['seconds' => $seconds])],
            ]);
        }

        if ($code = $request->input('code')) {
            $code = trim($code);
            // Vérifier d'abord le code TOTP si activé
            if ($user->two_factor_secret && $this->twoFactor->verify(decrypt($user->two_factor_secret), $code)) {
                return $this->handleSuccessfulLogin($request, $user, $key);
            }

            // Vérifier le code envoyé par email (obligatoire)
            $emailCode = $request->session()->get('auth.two_factor_email_code');

            \Illuminate\Support\Facades\Log::info('Tentative de validation OTP', [
                'user_id' => $user->id,
                'input_code' => $code,
                'session_code' => $emailCode['code'] ?? 'NULL',
                'expires_at' => $emailCode['expires_at'] ?? 'NULL',
                'is_expired' => isset($emailCode['expires_at']) ? now()->greaterThan($emailCode['expires_at']) : 'N/A',
                'match' => isset($emailCode['code']) && $emailCode['code'] === $code
            ]);

            if ($emailCode && $emailCode['code'] === $code && now()->lessThan($emailCode['expires_at'])) {
                return $this->handleSuccessfulLogin($request, $user, $key);
            }
        } elseif ($recoveryCode = $request->input('recovery_code')) {
            if ($user->two_factor_recovery_codes) {
                $codes = json_decode(decrypt($user->two_factor_recovery_codes), true);

                if (($index = array_search($recoveryCode, $codes)) !== false) {
                    unset($codes[$index]);

                    $user->forceFill([
                        'two_factor_recovery_codes' => encrypt(json_encode(array_values($codes))),
                    ])->save();

                    return $this->handleSuccessfulLogin($request, $user, $key);
                }
            }
        }

        \Illuminate\Support\Facades\RateLimiter::hit($key);

        throw ValidationException::withMessages([
            'code' => [__('Le code fourni est invalide ou a expiré.')],
        ]);
    }

    protected function handleSuccessfulLogin(Request $request, $user, $key)
    {
        \Illuminate\Support\Facades\RateLimiter::clear($key);

        // Si l'email n'est pas encore vérifié, on le vérifie maintenant car l'OTP est correct
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        Auth::login($user);

        $request->session()->regenerate();
        $request->session()->put('auth.two_factor_verified', true);
        $request->session()->forget(['auth.2fa_user_id', 'auth.two_factor_email_code']);

        // Dispatch login event
        event(new \App\Events\UserLoggedIn($user, '2fa_verified'));

        return redirect()->intended('/dashboard');
    }

    public function sendEmailCode(Request $request)
    {
        $userId = $request->session()->get('auth.2fa_user_id') ?? Auth::id();

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::findOrFail($userId);
        $key = 'two-factor-email-send:' . $user->id;

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 1)) {
            return redirect()->route('two-factor.login')->with('error', __('Veuillez patienter avant de demander un nouveau code.'));
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $request->session()->put('auth.two_factor_email_code', [
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            $user->notify(new \App\Notifications\TwoFactorCode($code));
            \Illuminate\Support\Facades\RateLimiter::hit($key, 60); // 1 minute throttle
            return redirect()->route('two-factor.login')->with('status', 'code-sent');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur de renvoi OTP : ' . $e->getMessage());
            return redirect()->route('two-factor.login')->with('error', 'Erreur lors de l\'envoi du code. Veuillez vérifier votre configuration SMTP.');
        }
    }
}
