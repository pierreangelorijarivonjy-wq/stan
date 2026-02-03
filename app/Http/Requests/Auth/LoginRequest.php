<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['nullable', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $email = strtolower(trim($this->email));
        $user = \App\Models\User::where('email', $email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email inexistant',
            ]);
        }

        // Vérifier si c'est un étudiant ou un membre du personnel autorisé
        $staffRoles = ['admin', 'comptable', 'scolarite', 'controleur'];
        $isStaff = $user->hasAnyRole($staffRoles);
        $isStudent = $user->hasRole('student');

        if (!$isStudent && !$isStaff) {
            \Illuminate\Support\Facades\Log::warning('Tentative de connexion non autorisée', [
                'email' => $email,
                'ip' => $this->ip(),
                'roles' => $user->getRoleNames(),
            ]);

            throw ValidationException::withMessages([
                'email' => 'Accès refusé. Ce portail est réservé aux étudiants et au personnel autorisé.',
            ]);
        }

        // Vérification du mot de passe
        if ($isStaff) {
            if (empty($this->password)) {
                throw ValidationException::withMessages([
                    'password' => 'Le mot de passe est requis pour le personnel.',
                ]);
            }

            if (
                !Auth::guard('web')->validate([
                    'email' => $this->email,
                    'password' => $this->password,
                ])
            ) {
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'email' => __('auth.failed'),
                ]);
            }
        } else {
            // Pour les étudiants, le mot de passe est optionnel s'ils utilisent l'OTP
            // Mais s'ils en fournissent un, on le valide
            if (!empty($this->password)) {
                if (
                    !Auth::guard('web')->validate([
                        'email' => $this->email,
                        'password' => $this->password,
                    ])
                ) {
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages([
                        'email' => __('auth.failed'),
                    ]);
                }
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
