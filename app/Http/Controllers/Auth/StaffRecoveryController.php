<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffRecoveryController extends Controller
{
    /**
     * Display the recovery view.
     */
    public function create()
    {
        return view('auth.staff-recovery');
    }

    /**
     * Handle the recovery request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'matricule' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::verifyStaffRecovery($request->email, $request->matricule);

        if (!$user) {
            return back()->withErrors([
                'matricule' => 'Le matricule ou l\'email est incorrect.',
            ]);
        }

        // Vérifier si c'est bien un membre du personnel
        if ($user->hasRole('student')) {
            return back()->withErrors([
                'email' => 'Cette méthode de récupération est réservée au personnel administratif.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('status', 'Votre mot de passe a été réinitialisé avec succès.');
    }
}
