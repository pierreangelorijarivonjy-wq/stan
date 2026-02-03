<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],
            'phone' => ['required', 'string', 'max:20'],
            'matricule' => ['required_if:role_type,student', 'nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role_type' => ['required', 'in:student,staff'],
            'staff_role' => ['required_if:role_type,staff', 'nullable', 'in:admin,comptable,scolarite,controleur,admin_it'],
            'cin' => ['nullable', 'image', 'max:10240'], // 10MB
            'photo' => ['nullable', 'image', 'max:10240'], // 10MB
        ]);

        $isStaff = $request->role_type === 'staff';

        // Restriction Admin : Seul "Stan" peut créer un compte Admin
        if ($isStaff && $request->staff_role === 'admin') {
            // On vérifie si l'email correspond à celui de Stan (ou une autre logique de sécurité)
            // Ici, on bloque simplement car l'admin ne devrait pas s'inscrire publiquement
            return back()->withErrors(['staff_role' => 'La création de compte Administrateur est restreinte.'])->withInput();
        }

        // Handle file uploads
        $cinPath = $request->file('cin') ? $request->file('cin')->store('identity/cin', 'public') : null;
        $photoPath = $request->file('photo') ? $request->file('photo')->store('identity/photos', 'public') : null;

        // Matricule association logic
        $matriculeStatus = 'pending';
        $student = null;
        if (!$isStaff && $request->matricule) {
            $student = \App\Models\Student::where('matricule', $request->matricule)->first();
            if ($student) {
                $matriculeStatus = 'associated';
            } else {
                $matriculeStatus = 'failed';
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => $isStaff ? 'pending' : 'active',
            'requested_role' => $isStaff ? $request->staff_role : null,
            'matricule' => $request->matricule,
            'matricule_association_status' => $matriculeStatus,
            'cin_path' => $cinPath,
            'photo_path' => $photoPath,
        ]);

        if (!$isStaff) {
            $user->assignRole('student');

            // Link user to student record if found
            if ($student) {
                $student->update(['user_id' => $user->id]);
            }

            Auth::login($user);
        }

        try {
            event(new Registered($user));

            if ($isStaff) {
                // Notifier les admins existants
                $admins = User::role('admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\StaffRegistrationRequested($user));
                }

                return redirect()->route('login')
                    ->with('success', 'Votre demande d\'inscription en tant que staff a été envoyée pour validation. Vous recevrez un email une fois approuvée.');
            }

            // Pour les étudiants : envoyer OTP
            $otpService = app(\App\Services\OtpService::class);
            $otpService->sendOtp($user);

            return redirect()->route('otp.show')
                ->with('success', 'Inscription réussie ! Un code de vérification a été envoyé à votre email.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors de l\'inscription : ' . $e->getMessage());

            if ($isStaff) {
                return redirect()->route('login')
                    ->with('success', 'Demande envoyée, mais une erreur est survenue lors de la notification.');
            }

            return redirect()->route('login')
                ->with('error', 'Compte créé, mais une erreur est survenue lors de l\'envoi de l\'email.');
        }
    }
}
