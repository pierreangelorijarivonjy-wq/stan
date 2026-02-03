<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use Auditable;

    protected $fillable = [
        'student_id',
        'exam_session_id',
        'subject',
        'grade',
        'status',
        'metadata'
    ];

    protected $casts = [
        'grade' => 'decimal:2',
        'metadata' => 'array'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class);
    }
}
