<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\TwoFactorCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailTwoFactorTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_email_otp_code()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $user = User::factory()->create();

        \Illuminate\Support\Facades\RateLimiter::clear('two-factor-email-send:' . $user->id);

        $response = $this->withSession(['login.id' => $user->id])
            ->post(route('otp.resend'));

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertNotNull($user->fresh()->otp_code);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\OtpMail::class);
    }

    public function test_user_can_authenticate_with_email_two_factor_code()
    {
        $user = User::factory()->create();

        $code = '123456';

        $user->update([
            'otp_code' => $code,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->withSession([
            'login.id' => $user->id,
        ])->post(route('otp.verify'), [
                    'otp' => $code,
                ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
        $this->assertNull(session('auth.two_factor_email_code'));
        $this->assertNull(session('auth.2fa_user_id'));
    }

    public function test_email_two_factor_code_expires()
    {
        $user = User::factory()->create();

        $code = '123456';

        $user->update([
            'otp_code' => $code,
            'otp_expires_at' => now()->subMinutes(1),
        ]);

        $response = $this->from(route('otp.show'))->withSession([
            'login.id' => $user->id,
        ])->post(route('otp.verify'), [
                    'otp' => $code,
                ]);

        $response->assertRedirect(route('otp.show'));
        $response->assertSessionHasErrors('otp');
        $this->assertGuest();
    }
}
