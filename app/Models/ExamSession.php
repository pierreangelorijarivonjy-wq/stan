<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class ExamSession extends Model
{
    use HasFactory, Auditable;

    protected $table = 'exam_sessions';

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
        return $this->hasMany(Convocation::class, 'exam_session_id');
    }
}
