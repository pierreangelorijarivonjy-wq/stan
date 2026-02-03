<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'file_path',
        'file_type',
        'file_size',
        'download_count',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function incrementDownloads()
    {
        $this->increment('download_count');
    }
}
