<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentService
{
    protected $http;

    public function __construct()
    {
        // No explicit client needed with Facade
    }

    /**
     * Initier un paiement
     */
    public function initiate(User $user, string $provider, string $phone, float $amount, string $type = 'frais_scolarite')
    {
        $reference = 'EDUPASS-' . time() . '-' . $user->id;

        // Création de l'enregistrement paiement
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'phone' => $phone,
            'provider' => $provider,
            'transaction_id' => $reference,
            'type' => $type,
            'status' => 'pending'
        ]);

        try {
            if ($provider === 'mvola') {
                return $this->processMvola($payment, $phone, $amount, $reference);
            }

            if ($provider === 'orange') {
                return $this->processOrange($payment, $phone, $amount, $reference);
            }

            if ($provider === 'airtel' || $provider === 'card') {
                // Stub pour Airtel et Carte Bancaire
                Log::info("🔴 {$provider} PAYMENT FLOW STARTED (STUB)", [
                    'phone' => $phone,
                    'amount' => $amount,
                    'reference' => $reference
                ]);
                return ['redirect' => route('payment.waiting', ['payment' => $payment->id])];
            }

            if ($provider === 'transfer') {
                // Flow pour Virement : redirection vers upload de preuve
                return ['redirect' => route('payments') . '#upload-proof'];
            }

            throw new \Exception("Fournisseur de paiement non supporté : {$provider}");

        } catch (\Exception $e) {
            Log::error('🔴 ERREUR PAIEMENT SERVICE', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $payment->update(['status' => 'failed', 'metadata' => ['error' => $e->getMessage()]]);
            throw $e;
        }
    }

    private function processMvola(Payment $payment, $phone, $amount, $reference)
    {
        Log::info('🔵 MVOLA PAYMENT FLOW STARTED', [
            'phone' => $phone,
            'amount' => $amount,
            'reference' => $reference
        ]);

        $token = $this->getMVolaToken();
        $baseUrl = config('services.mvola.base_url');
        // Nettoyage de l'URL de base pour éviter les doublons
        $baseUrl = rtrim($baseUrl, '/');
        if (str_contains($baseUrl, '/mvola/mm/transactions')) {
            $endpoint = $baseUrl;
        } else {
            $endpoint = "{$baseUrl}/mvola/mm/transactions/type/merchantpay/1.0.0";
        }

        $merchantPhone = config('services.mvola.merchant_msisdn');
        $callbackUrl = config('services.mvola.callback_url') ?? route('webhook.mvola');

        // Formatage des numéros
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        $cleanMerchant = preg_replace('/[^0-9]/', '', $merchantPhone);

        $debitMsisdn = preg_match('/^261/', $cleanPhone) ? $cleanPhone : '261' . ltrim($cleanPhone, '0');
        $creditMsisdn = preg_match('/^261/', $cleanMerchant) ? $cleanMerchant : '261' . ltrim($cleanMerchant, '0');

        $correlationId = (string) Str::uuid();

        // Payload Body
        $payload = [
            "amount" => (string) intval($amount),
            "currency" => "Ar",
            "descriptionText" => "Paiement EduPass",
            "requestDate" => now()->format('Y-m-d\TH:i:s.v\Z'),
            "debitParty" => [
                ["key" => "msisdn", "value" => $debitMsisdn]
            ],
            "creditParty" => [
                ["key" => "msisdn", "value" => $creditMsisdn]
            ],
            "metadata" => [
                ["key" => "partnerName", "value" => "EduPass-MG"],
                ["key" => "reference", "value" => $reference]
            ],
            "requestingOrganisationTransactionReference" => $reference,
            "originalTransactionReference" => $reference
        ];

        Log::info('🔵 MVOLA PAYLOAD', $payload);

        try {
            // Using Laravel HTTP Facade (Guzzle wrapper)
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => "Bearer $token",
                'Version' => '1.0',
                'X-CorrelationID' => $correlationId,
                'UserLanguage' => 'FR',
                'UserAccountIdentifier' => "msisdn;{$creditMsisdn}",
                'partnerName' => 'EduPass-MG',
                'Content-Type' => 'application/json',
                'Cache-Control' => 'no-cache',
                'X-Callback-URL' => $callbackUrl,
            ])
            ->withoutVerifying() // Desactive SSL verify pour dev/tests (équivalent à 'verify' => false)
            ->post($endpoint, $payload);

            $statusCode = $response->status();
            $responseBody = $response->json();
            
            Log::info('🟢 MVOLA API RESPONSE', ['status' => $statusCode, 'body' => $responseBody]);

            if ($statusCode === 200 && isset($responseBody['serverCorrelationId'])) {
                $metadata = $payment->metadata ?? [];
                $metadata['serverCorrelationId'] = $responseBody['serverCorrelationId'];
                $metadata['mvola_status'] = $responseBody['status'] ?? 'pending';
                
                $payment->update([
                    'metadata' => $metadata
                ]);
            } else {
                Log::warning('🟠 MVOLA API WARNING: Unexpected response structure', $responseBody);
            }

            return ['redirect' => route('payment.waiting', ['payment' => $payment->id])];

        } catch (\Exception $e) {
            Log::error('🔴 MVOLA API ERROR', ['message' => $e->getMessage()]);
            throw new \Exception("Erreur MVola: " . $e->getMessage());
        }
    }

    /**
     * Check Transaction Status (V2 Beta)
     */
    public function checkMvolaStatus(Payment $payment)
    {
        $serverCorrelationId = $payment->metadata['serverCorrelationId'] ?? null;
        if (!$serverCorrelationId) {
            return null;
        }

        $token = $this->getMVolaToken();
        $baseUrl = config('services.mvola.base_url');
        $baseUrl = rtrim($baseUrl, '/');
        $endpoint = "{$baseUrl}/mvola/mm/transactions/type/merchantpay/1.0.0/status/{$serverCorrelationId}";
        
        $merchantPhone = config('services.mvola.merchant_msisdn');
        $cleanMerchant = preg_replace('/[^0-9]/', '', $merchantPhone);
        $creditMsisdn = preg_match('/^261/', $cleanMerchant) ? $cleanMerchant : '261' . ltrim($cleanMerchant, '0');

        $correlationId = (string) Str::uuid();

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'Authorization' => "Bearer $token",
                    'Version' => '1.0',
                    'X-CorrelationID' => $correlationId,
                    'UserLanguage' => 'FR',
                    'UserAccountIdentifier' => "msisdn;{$creditMsisdn}",
                    'partnerName' => 'EduPass-MG',
                    'Content-Type' => 'application/json',
                    'Cache-Control' => 'no-cache',
                ])
                ->withoutVerifying()
                ->get($endpoint);

            $body = $response->json();
            Log::info('🔵 MVOLA CHECK STATUS RESPONSE', $body);
            
            return $body;

        } catch (\Exception $e) {
            Log::error('🔴 MVOLA CHECK STATUS ERROR: ' . $e->getMessage());
            return null;
        }
    }

    private function processOrange(Payment $payment, $phone, $amount, $reference)
    {
        $token = $this->getOrangeToken();

        $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => "Bearer $token",
                'Content-Type' => 'application/json',
            ])
            ->withoutVerifying()
            ->post(config('services.orange.base_url') . '/orange-money-webpay/mg/v1/webpayment', [
                "merchant_key" => config('services.orange.merchant_key'),
                "currency" => "MGA",
                "order_id" => $reference,
                "amount" => $amount,
                "return_url" => route('payment.success'),
                "cancel_url" => route('payment.cancel'),
                "notif_url" => config('services.orange.callback_url') ?? route('webhook.orange'),
                "customer" => $phone,
                "reference" => "EduPass-MG",
                "lang" => "fr"
            ]);

        $result = $response->object();

        if (isset($result->payment_url)) {
            return ['redirect' => $result->payment_url];
        }

        throw new \Exception("Impossible d'obtenir l'URL de paiement Orange");
    }

    private function getMVolaToken()
    {
        $clientId = config('services.mvola.client_id');
        $clientSecret = config('services.mvola.client_secret');
        $authUrl = config('services.mvola.auth_url');

        $response = \Illuminate\Support\Facades\Http::withBasicAuth($clientId, $clientSecret)
            ->withoutVerifying()
            ->asForm()
            ->post($authUrl, [
                'grant_type' => 'client_credentials',
                'scope' => 'EXT_INT_MVOLA_SCOPE'
            ]);

        if (!$response->successful()) {
            throw new \Exception("Erreur authentification MVola: " . $response->body());
        }

        return $response->json()['access_token'];
    }

    private function getOrangeToken()
    {
        $clientId = config('services.orange.client_id');
        $clientSecret = config('services.orange.client_secret');
        $authUrl = config('services.orange.auth_url');

        $response = \Illuminate\Support\Facades\Http::withBasicAuth($clientId, $clientSecret)
            ->withoutVerifying()
            ->asForm()
            ->post($authUrl, ['grant_type' => 'client_credentials']);

        return $response->json()['access_token'];
    }
}
