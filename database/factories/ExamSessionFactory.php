<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExamSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => 'exam',
            'date' => now()->addDays(30),
            'time' => '08:00',
            'center' => 'Lycée Moderne',
            'status' => 'planned',
        ];
    }
}
