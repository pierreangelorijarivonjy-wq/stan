<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::find(6);
if ($user) {
    echo "Recovery Codes for " . $user->email . ":\n";
    echo implode("\n", $user->recoveryCodes()) . "\n";
} else {
    echo "User 6 not found.\n";
    $user = App\Models\User::first();
    if ($user) {
        echo "Recovery Codes for " . $user->email . ":\n";
        echo implode("\n", $user->recoveryCodes()) . "\n";
    }
}
