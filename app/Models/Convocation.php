<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class Convocation extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'student_id',
        'exam_session_id',
        'qr_code',
        'signature',
        'status',
        'scanned_at'
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class, 'exam_session_id');
    }
}
