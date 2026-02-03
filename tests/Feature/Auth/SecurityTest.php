<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_login_is_rate_limited()
    {
        $engine = new \PragmaRX\Google2FA\Google2FA();
        $secret = $engine->generateSecretKey();

        $user = User::factory()->create([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode([])),
            'two_factor_confirmed_at' => now(),
        ]);

        // Simuler 5 tentatives échouées
        for ($i = 0; $i < 5; $i++) {
            $this->actingAs($user)->post(route('two-factor.login'), [
                'code' => '000000',
            ]);
        }

        // La 6ème doit être bloquée
        $response = $this->actingAs($user)->post(route('two-factor.login'), [
            'code' => '000000',
        ]);

        $response->assertSessionHasErrors('code');
        // Vérifier le message d'erreur spécifique au throttling
        $this->assertStringContainsString('Trop de tentatives', session('errors')->first('code'));
    }
}
