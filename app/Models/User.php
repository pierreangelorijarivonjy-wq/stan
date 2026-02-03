<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use App\Traits\Auditable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'phone',
        'password',
        'otp_code',
        'otp_expires_at',
        'email_verified_via_otp',
        'last_login_at',
        'status',
        'requested_role',
        'matricule',
        'matricule_association_status',
        'cin_path',
        'photo_path',
        'trust_score',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
            'email_verified_via_otp' => 'boolean',
            'last_login_at' => 'datetime',
            'phone' => 'encrypted',
        ];
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            // La limite sera vérifiée lors de l'assignation des rôles
            // ou via une méthode de validation explicite avant création.
        });
    }

    /**
     * Valider et créer un compte staff avec limite.
     */
    public static function createStaff(array $data, string $role): self
    {
        if (static::isRoleLimitReached($role)) {
            throw new \Exception("La limite de 3 comptes pour le rôle {$role} est atteinte.");
        }

        $user = static::create($data);
        $user->assignRole($role);
        return $user;
    }

    /**
     * Vérifier si un rôle a atteint sa limite de 3 comptes.
     */
    public static function isRoleLimitReached(string $roleName): bool
    {
        if (in_array($roleName, ['admin', 'comptable', 'scolarite'])) {
            return static::role($roleName)->count() >= 3;
        }
        return false;
    }

    /**
     * Vérifier les identifiants de récupération pour le staff.
     */
    public static function verifyStaffRecovery(string $email, string $matricule): ?self
    {
        return static::where('email', $email)
            ->where('matricule', $matricule)
            ->first();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function hasEnabledTwoFactorAuthentication(): bool
    {
        return !is_null($this->two_factor_secret) &&
            !is_null($this->two_factor_confirmed_at);
    }

    public function recoveryCodes(): array
    {
        return json_decode(decrypt($this->two_factor_recovery_codes), true);
    }

    /**
     * Override the default verification email.
     * We use OTP for verification now.
     */
    public function sendEmailVerificationNotification()
    {
        // Do nothing
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class)->withPivot('awarded_at');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
