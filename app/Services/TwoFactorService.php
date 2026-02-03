<?php

namespace App\Services;

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TwoFactorService
{
    protected $engine;

    public function __construct(Google2FA $engine)
    {
        $this->engine = $engine;
    }

    public function generateSecretKey(): string
    {
        return $this->engine->generateSecretKey();
    }

    public function qrCodeUrl(string $companyName, string $companyEmail, string $secret): string
    {
        return \Endroid\QrCode\Builder\Builder::create()
            ->data($this->engine->getQRCodeUrl($companyName, $companyEmail, $secret))
            ->encoding(new \Endroid\QrCode\Encoding\Encoding('UTF-8'))
            ->size(200)
            ->margin(10)
            ->build()
            ->getDataUri();
    }

    public function verify(string $secret, string $code): bool
    {
        return $this->engine->verifyKey($secret, $code);
    }

    public function generateRecoveryCodes(): array
    {
        return Collection::times(8, function () {
            return Str::random(10) . '-' . Str::random(10);
        })->all();
    }
}
