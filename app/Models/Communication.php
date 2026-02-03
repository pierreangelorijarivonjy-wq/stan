<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Auditable;

class Communication extends Model
{
    use Auditable;

    protected $fillable = [
        'title',
        'content',
        'type',
        'channels',
        'target',
        'scheduled_at',
        'sent_at',
        'status',
        'metadata'
    ];

    protected $casts = [
        'channels' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'metadata' => 'array',
    ];
}
