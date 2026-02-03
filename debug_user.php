<?php

use App\Models\User;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'a60242792@gmail.com';
$user = User::where('email', $email)->first();

if ($user) {
    echo "Found exactly: [" . $user->email . "]\n";
    echo "Length: " . strlen($user->email) . "\n";
    echo "Verified: " . ($user->email_verified_at ? 'Yes' : 'No') . "\n";
} else {
    echo "Not found with exact match.\n";
    $allUsers = User::all();
    foreach ($allUsers as $u) {
        if (trim(strtolower($u->email)) == trim(strtolower($email))) {
            echo "Found similar: [" . $u->email . "] (Length: " . strlen($u->email) . ")\n";
        }
    }
}
