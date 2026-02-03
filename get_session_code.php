<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$sessions = DB::table('sessions')->orderBy('last_activity', 'desc')->limit(5)->get();

foreach ($sessions as $session) {
    $payload = unserialize(base64_decode($session->payload));
    if (isset($payload['auth']['two_factor_email_code'])) {
        echo "Session ID: {$session->id} | Code: " . $payload['auth']['two_factor_email_code']['code'] . " | Expires: " . $payload['auth']['two_factor_email_code']['expires_at'] . PHP_EOL;
    }
}
