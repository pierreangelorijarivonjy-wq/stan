<?php

use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

$u = User::find(6);
if (!$u) {
    echo "User 6 not found" . PHP_EOL;
    return;
}

echo "User 6 found: " . $u->email . PHP_EOL;

$s = $u->student;
if ($s) {
    echo "Student found for user 6" . PHP_EOL;

    $rawPhone = DB::table('students')->where('id', $s->id)->value('phone');
    $rawPieceId = DB::table('students')->where('id', $s->id)->value('piece_id');

    echo "Raw Phone: " . substr($rawPhone, 0, 20) . "..." . PHP_EOL;

    try {
        $decrypted = Crypt::decryptString($rawPhone);
        echo "Phone decrypted successfully: " . $decrypted . PHP_EOL;
    } catch (DecryptException $e) {
        echo "Phone decryption FAILED: " . $e->getMessage() . PHP_EOL;
    }

    try {
        $decrypted = Crypt::decryptString($rawPieceId);
        echo "Piece ID decrypted successfully: " . $decrypted . PHP_EOL;
    } catch (DecryptException $e) {
        echo "Piece ID decryption FAILED: " . $e->getMessage() . PHP_EOL;
    }
} else {
    echo "No student found for user 6" . PHP_EOL;
}

$payments = $u->payments;
echo "User 6 has " . $payments->count() . " payments" . PHP_EOL;
foreach ($payments as $p) {
    $rawPhone = DB::table('payments')->where('id', $p->id)->value('phone');
    if (!$rawPhone) {
        echo "Payment {$p->id} has NO phone number" . PHP_EOL;
        continue;
    }
    try {
        Crypt::decryptString($rawPhone);
        echo "Payment {$p->id} phone decrypted successfully" . PHP_EOL;
    } catch (DecryptException $e) {
        echo "Payment {$p->id} phone decryption FAILED: " . $e->getMessage() . PHP_EOL;
        echo "Raw value: " . substr($rawPhone, 0, 30) . "..." . PHP_EOL;
    }
}
