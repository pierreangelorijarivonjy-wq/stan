<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    protected $twoFactor;

    public function __construct(TwoFactorService $twoFactor)
    {
        $this->twoFactor = $twoFactor;
    }

    public function enable(Request $request)
    {
        $user = $request->user();

        // Si déjà confirmé, rediriger
        if ($user->two_factor_confirmed_at) {
            return back()->with('status', 'two-factor-authentication-enabled');
        }

        // Générer secret si pas encore fait
        if (!$user->two_factor_secret) {
            $user->forceFill([
                'two_factor_secret' => encrypt($this->twoFactor->generateSecretKey()),
                'two_factor_recovery_codes' => encrypt(json_encode($this->twoFactor->generateRecoveryCodes())),
            ])->save();
        }

        return view('auth.two-factor-enable', [
            'qrCode' => $this->twoFactor->qrCodeUrl(
                config('app.name'),
                $user->email,
                decrypt($user->two_factor_secret)
            ),
            'secret' => decrypt($user->two_factor_secret),
        ]);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if (!$this->twoFactor->verify(decrypt($user->two_factor_secret), $request->code)) {
            throw ValidationException::withMessages([
                'code' => __('Le code fourni est invalide.'),
            ]);
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
        ])->save();

        return redirect()->route('profile.edit')->with('status', 'two-factor-authentication-confirmed');
    }

    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return back()->with('status', 'two-factor-authentication-disabled');
    }

    public function regenerate(Request $request)
    {
        $request->user()->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode($this->twoFactor->generateRecoveryCodes())),
        ])->save();

        return back()->with('status', 'recovery-codes-regenerated');
    }

    public function showRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        return response()->json([
            'codes' => json_decode(decrypt($request->user()->two_factor_recovery_codes), true)
        ]);
    }
}
