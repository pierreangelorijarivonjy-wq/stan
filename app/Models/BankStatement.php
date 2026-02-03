<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class BankStatement extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'source',
        'date',
        'reference',
        'amount',
        'status',
        'raw_data'
    ];

    protected $casts = [
        'date' => 'date',
        'raw_data' => 'array',
    ];

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'transaction_id', 'reference');
    }
}
