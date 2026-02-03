<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\Auditable;

class Student extends Model
{
    use HasFactory, Auditable;
    protected $fillable = [
        'user_id',
        'matricule',
        'first_name',
        'last_name',
        'email',
        'phone',
        'piece_id',
        'photo',
        'status'
    ];

    protected $casts = [
        'phone' => 'encrypted',
        'piece_id' => 'encrypted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id', 'user_id');
    }

    public function convocations(): HasMany
    {
        return $this->hasMany(Convocation::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('status')
            ->withTimestamps();
    }
}
