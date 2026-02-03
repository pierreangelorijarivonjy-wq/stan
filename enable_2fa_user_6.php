<?php

use App\Models\User;
use App\Services\TwoFactorService;

$u = User::find(6);
if (!$u) {
    echo "User 6 not found" . PHP_EOL;
    return;
}

$service = app(TwoFactorService::class);
$secret = $service->generateSecretKey();
$recoveryCodes = $service->generateRecoveryCodes();

$u->forceFill([
    'two_factor_secret' => encrypt($secret),
    'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
    'two_factor_confirmed_at' => now(),
])->save();

echo "2FA enabled for user 6 ({$u->email})" . PHP_EOL;
echo "Secret: " . $secret . PHP_EOL;
echo "Recovery Codes: " . implode(', ', $recoveryCodes) . PHP_EOL;
