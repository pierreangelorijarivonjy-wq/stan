<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OtpReproductionTest extends TestCase
{
    use RefreshDatabase;

    public function test_otp_validation_works_with_correct_code()
    {
        $user = User::factory()->create([
            'email' => 'test@edupass.mg',
        ]);
        $user->assignRole('student');

        // Simulate login and OTP generation
        $response = $this->post('/login', [
            'email' => 'test@edupass.mg',
        ]);

        $response->assertRedirect(route('two-factor.login'));

        $code = session('auth.two_factor_email_code')['code'];
        $this->assertNotNull($code);

        // Validate OTP
        $response = $this->post('/two-factor-challenge', [
            'code' => $code,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertTrue(session('auth.two_factor_verified'));
    }

    public function test_otp_validation_fails_with_incorrect_code()
    {
        $user = User::factory()->create([
            'email' => 'test@edupass.mg',
        ]);

        $this->post('/login', [
            'email' => 'test@edupass.mg',
        ]);

        $response = $this->post('/two-factor-challenge', [
            'code' => '000000',
        ]);

        $response->assertSessionHasErrors('code');
    }
}
