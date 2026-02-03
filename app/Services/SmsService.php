<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $provider;
    protected $apiKey;
    protected $senderId;

    public function __construct()
    {
        $this->provider = config('services.sms.provider', 'log'); // Default to log
        $this->apiKey = config('services.sms.api_key');
        $this->senderId = config('services.sms.sender_id', 'EduPass');
    }

    public function send(string $phone, string $message)
    {
        // Nettoyage du numéro
        $phone = $this->formatPhone($phone);

        if ($this->provider === 'log') {
            Log::info("SMS SIMULATION to {$phone}: {$message}");
            return true;
        }

        if ($this->provider === 'nexah') {
            return $this->sendViaNexah($phone, $message);
        }

        Log::warning("SMS Provider {$this->provider} not supported.");
        return false;
    }

    protected function sendViaNexah($phone, $message)
    {
        try {
            $response = Http::post(config('services.sms.api_url'), [
                'api_key' => $this->apiKey,
                'sender' => $this->senderId,
                'phone' => $phone,
                'message' => $message
            ]);

            if ($response->successful()) {
                Log::info("SMS sent via Nexah to {$phone}");
                return true;
            }

            Log::error("Nexah SMS Error: " . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error("SMS Exception: " . $e->getMessage());
            return false;
        }
    }

    protected function formatPhone($phone)
    {
        // Standardiser au format 26134...
        if (preg_match('/^03/', $phone)) {
            return preg_replace('/^0/', '261', $phone);
        }
        return $phone;
    }
}
