<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'instructor',
        'duration',
        'image',
        'level',
        'category',
        'status',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function enrollments()
    {
        return $this->hasMany(\App\Models\Enrollment::class);
    }
}
