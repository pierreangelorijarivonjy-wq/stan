<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class Payment extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'provider',
        'transaction_id',
        'status',
        'phone',
        'metadata',
        'paid_at',
        'reconciled_at',
        'reconciliation_score'
    ];

    protected $casts = [
        'metadata' => 'array',
        'paid_at' => 'datetime',
        'reconciled_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
