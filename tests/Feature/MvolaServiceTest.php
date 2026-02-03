<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

class MvolaServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PaymentService();
        
        // Mock Config
        Config::set('services.mvola.client_id', 'test_client_id');
        Config::set('services.mvola.client_secret', 'test_client_secret');
        Config::set('services.mvola.merchant_msisdn', '0340010000'); // Merchant
        Config::set('services.mvola.base_url', 'https://devapi.mvola.mg');
        Config::set('services.mvola.auth_url', 'https://devapi.mvola.mg/oauth2/token');
        Config::set('services.mvola.callback_url', 'https://edupass.mg/webhook/mvola');
    }

    public function test_mvola_initiate_transaction_sends_correct_payload_and_headers()
    {
        // 1. Mock Authentication & Transaction call
        Http::fake([
            // Mock Token Endpoint
            'https://devapi.mvola.mg/oauth2/token' => Http::response([
                'access_token' => 'fake_access_token',
                'expires_in' => 3600
            ], 200),

            // Mock Transaction Endpoint
            'https://devapi.mvola.mg/mvola/mm/transactions/type/merchantpay/1.0.0' => Http::response([
                'status' => 'pending',
                'serverCorrelationId' => 'server-ref-12345',
                'notificationMethod' => 'callback'
            ], 200),
        ]);

        // 2. Prepare Data
        $user = User::factory()->create();
        $userMsisdn = '0341234567'; // Customer
        $amount = 50000;

        // 3. Call Service
        $this->service->initiate($user, 'mvola', $userMsisdn, $amount);

        // 4. Verify Requests
        
        // Verify Auth Request
        Http::assertSent(function ($request) {
            return $request->url() == 'https://devapi.mvola.mg/oauth2/token' &&
                   $request->hasHeader('Authorization') &&
                   $request['grant_type'] == 'client_credentials';
        });

        // Verify Transaction Request (CRITICAL SPECS CHECK)
        Http::assertSent(function ($request) use ($amount, $userMsisdn) {
            $isTransactionEndpoint = $request->url() == 'https://devapi.mvola.mg/mvola/mm/transactions/type/merchantpay/1.0.0';
            
            if (!$isTransactionEndpoint) return false;

            // Headers Check
            $headers = $request->headers();
            $hasAuth = str_contains($headers['Authorization'][0], 'Bearer fake_access_token');
            $hasVersion = $headers['Version'][0] == '1.0';
            $hasCallback = $headers['X-Callback-URL'][0] == 'https://edupass.mg/webhook/mvola'; // V2 Spec Requirement
            $hasCorrelation = !empty($headers['X-CorrelationID'][0]);
            
            // Check UserAccountIdentifier uses MERCHANT number (0340010000 -> 261340010000)
            $merchantMsisdnFmt = '261340010000';
            $hasUserIdentifier = $headers['UserAccountIdentifier'][0] == "msisdn;{$merchantMsisdnFmt}";

            // Body Check
            $data = $request->data();
            $amountIsString = $data['amount'] === "50000"; // Spec: "Amount of transaction without decimals"
            $debitIsCustomer = $data['debitParty'][0]['value'] === '261341234567'; // Customer
            $creditIsMerchant = $data['creditParty'][0]['value'] === '261340010000'; // Merchant
            
            $requestingRef = !empty($data['requestingOrganisationTransactionReference']);
            $originalRef = !empty($data['originalTransactionReference']);

            return $hasAuth && 
                   $hasVersion && 
                   $hasCallback && 
                   $hasCorrelation && 
                   $hasUserIdentifier && 
                   $amountIsString && 
                   $debitIsCustomer && 
                   $creditIsMerchant &&
                   $requestingRef &&
                   $originalRef;
        });
    }

    public function test_mvola_initiate_saves_server_correlation_id()
    {
        Http::fake([
            '*' => Http::response([
                'access_token' => 'fake',
                'status' => 'pending',
                'serverCorrelationId' => 'server-ref-uuid-888',
            ], 200)
        ]);

        $user = User::factory()->create();
        $this->service->initiate($user, 'mvola', '0340000000', 1000);

        $payment = Payment::latest()->first();
        
        $this->assertEquals('server-ref-uuid-888', $payment->metadata['serverCorrelationId']);
        $this->assertEquals('pending', $payment->metadata['mvola_status']);
    }
}
