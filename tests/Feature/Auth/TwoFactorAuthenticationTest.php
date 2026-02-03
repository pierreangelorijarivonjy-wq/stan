<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TwoFactorAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_enable_two_factor_authentication()
    {
        $user = User::factory()->create();

        $response = $this->actingAsVerified($user)->post(route('two-factor.enable'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.two-factor-enable');

        $user->refresh();
        $this->assertNotNull($user->two_factor_secret);
        $this->assertNotNull($user->two_factor_recovery_codes);
        $this->assertNull($user->two_factor_confirmed_at);
    }

    public function test_user_can_confirm_two_factor_authentication()
    {
        $user = User::factory()->create();

        // Activer d'abord
        $this->actingAsVerified($user)->post(route('two-factor.enable'));
        $user->refresh();

        // Générer un code valide
        $service = app(TwoFactorService::class);
        $secret = decrypt($user->two_factor_secret);
        $code = (new \PragmaRX\Google2FA\Google2FA())->getCurrentOtp($secret);

        $response = $this->actingAsVerified($user)->post(route('two-factor.confirm'), [
            'code' => $code,
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('status', 'two-factor-authentication-confirmed');

        $user->refresh();
        $this->assertNotNull($user->two_factor_confirmed_at);
    }

    public function test_user_cannot_confirm_with_invalid_code()
    {
        $user = User::factory()->create();
        $this->actingAsVerified($user)->post(route('two-factor.enable'));

        $response = $this->actingAsVerified($user)->post(route('two-factor.confirm'), [
            'code' => '000000',
        ]);

        $response->assertSessionHasErrors('code');
        $user->refresh();
        $this->assertNull($user->two_factor_confirmed_at);
    }

    public function test_user_is_redirected_to_challenge_if_2fa_enabled()
    {
        $engine = new \PragmaRX\Google2FA\Google2FA();
        $secret = $engine->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode([])),
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('two-factor.login'));
    }

    public function test_user_can_authenticate_with_valid_code()
    {
        $engine = new \PragmaRX\Google2FA\Google2FA();
        $secret = $engine->generateSecretKey();
        $code = $engine->getCurrentOtp($secret);

        $user = User::factory()->create([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode([])),
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->post(route('two-factor.login'), [
                'code' => $code,
            ]);

        $response->assertRedirect('/dashboard');
        $this->assertTrue(session('auth.two_factor_verified'));
    }

    public function test_user_cannot_authenticate_with_invalid_code()
    {
        $engine = new \PragmaRX\Google2FA\Google2FA();
        $secret = $engine->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode([])),
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->post(route('two-factor.login'), [
                'code' => '000000',
            ]);

        $response->assertSessionHasErrors('code');
        $this->assertFalse(session()->has('auth.two_factor_verified'));
    }

    public function test_user_can_authenticate_with_recovery_code()
    {
        $engine = new \PragmaRX\Google2FA\Google2FA();
        $secret = $engine->generateSecretKey();
        $recoveryCode = 'recovery-code';

        $user = User::factory()->create([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode([$recoveryCode])),
            'two_factor_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->post(route('two-factor.login'), [
                'recovery_code' => $recoveryCode,
            ]);

        $response->assertRedirect('/dashboard');
        $this->assertTrue(session('auth.two_factor_verified'));

        $user->refresh();
        $codes = json_decode(decrypt($user->two_factor_recovery_codes), true);
        $this->assertNotContains($recoveryCode, $codes);
    }
}
