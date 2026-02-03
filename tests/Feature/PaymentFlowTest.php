<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create necessary roles
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'student']);

        // Configurer les secrets pour les tests via config() helper
        config(['services.mvola.webhook_secret' => 'test_secret']);
        config(['services.orange.webhook_secret' => 'test_secret']);
    }

    public function test_user_can_initiate_payment()
    {
        $user = User::factory()->create();

        // Mock du PaymentService
        $this->mock(PaymentService::class, function ($mock) {
            $mock->shouldReceive('initiate')
                ->once()
                ->andReturn(['redirect' => 'http://payment.url']);
        });

        $response = $this->actingAsVerified($user)->post(route('payment.initiate'), [
            'provider' => 'mvola',
            'phone' => '0340000000',
            'amount' => 50000,
        ]);

        $response->assertRedirect('http://payment.url');
    }

    public function test_webhook_confirms_payment_with_valid_signature()
    {
        $user = User::factory()->create();
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => 50000,
            'phone' => '0340000000',
            'provider' => 'mvola',
            'transaction_id' => 'REF-123456',
            'type' => 'frais_scolarite',
            'status' => 'pending'
        ]);

        $payload = json_encode([
            'transactionStatus' => 'completed',
            'requestingOrganisationTransactionReference' => 'REF-123456',
            'amount' => 50000
        ]);

        $signature = hash_hmac('sha256', $payload, 'test_secret');

        $this->withoutExceptionHandling();
        $response = $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->postJson(route('webhook.mvola'), json_decode($payload, true), [
                'X-Signature' => $signature
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'paid',
        ]);
    }

    public function test_webhook_rejects_invalid_signature()
    {
        $payload = json_encode(['data' => 'fake']);
        $signature = 'invalid_signature';

        $response = $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->postJson(route('webhook.mvola'), json_decode($payload, true), [
                'X-Signature' => $signature
            ]);

        $response->assertStatus(403);
    }
}
