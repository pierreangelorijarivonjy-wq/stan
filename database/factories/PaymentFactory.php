<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => 'scolarite',
            'provider' => 'mvola',
            'phone' => '0340000001',
            'amount' => 100000,
            'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
            'status' => 'pending',
            'method' => 'mobile_money',
        ];
    }
}
