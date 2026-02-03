<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login with email/matricule and send OTP
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string', // email or matricule
            'password' => 'nullable|string',
        ]);

        $user = User::where('email', $request->identifier)
            ->orWhereHas('student', function ($q) use ($request) {
                $q->where('matricule', $request->identifier);
            })
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'identifier' => ['Utilisateur non trouvé.'],
            ]);
        }

        // For staff: require password
        if ($user->hasAnyRole(['admin', 'comptable', 'scolarite', 'controleur'])) {
            if (!$request->password || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'password' => ['Mot de passe incorrect.'],
                ]);
            }

            // Staff: use matricule as OTP
            $otp = $user->hasRole('admin') ? 'ADM-UF-2025-001' :
                ($user->hasRole('comptable') ? 'COM-UF-2025-001' : 'SCO-UF-2025-001');

            return response()->json([
                'message' => 'OTP envoyé',
                'otp_required' => true,
                'user_id' => $user->id,
                'otp_hint' => 'Utilisez votre matricule comme code',
            ]);
        }

        // For students: send OTP via email/SMS
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update(['otp_code' => $otp, 'otp_expires_at' => now()->addMinutes(10)]);

        // TODO: Send OTP via email/SMS
        // Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json([
            'message' => 'OTP envoyé par email',
            'otp_required' => true,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Verify OTP and issue token
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);

        // For staff: verify matricule
        if ($user->hasAnyRole(['admin', 'comptable', 'scolarite', 'controleur'])) {
            $expectedOtp = $user->hasRole('admin') ? 'ADM-UF-2025-001' :
                ($user->hasRole('comptable') ? 'COM-UF-2025-001' : 'SCO-UF-2025-001');

            if ($request->otp !== $expectedOtp) {
                throw ValidationException::withMessages([
                    'otp' => ['Code OTP invalide.'],
                ]);
            }
        } else {
            // For students: verify OTP from database
            if ($user->otp_code !== $request->otp || $user->otp_expires_at < now()) {
                throw ValidationException::withMessages([
                    'otp' => ['Code OTP invalide ou expiré.'],
                ]);
            }

            $user->update(['otp_code' => null, 'otp_expires_at' => null]);
        }

        // Create token
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Authentification réussie',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
            ],
        ]);
    }

    /**
     * Logout (revoke token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }

    /**
     * Get authenticated user
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('student', 'roles'),
        ]);
    }
}
