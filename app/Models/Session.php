<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Session extends Model
{
    protected $fillable = [
        'type',
        'center',
        'date',
        'time',
        'room',
        'rules',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'rules' => 'array',
    ];

    public function convocations(): HasMany
    {
        return $this->hasMany(Convocation::class);
    }
}
