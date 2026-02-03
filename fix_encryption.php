<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

echo "Démarrage de la migration des données chiffrées (via DB)..." . PHP_EOL;

// Migration des étudiants
$students = DB::table('students')->get();
foreach ($students as $student) {
    $updates = [];

    // Téléphone
    if ($student->phone) {
        try {
            Crypt::decryptString($student->phone);
        } catch (DecryptException $e) {
            echo "Chiffrement du téléphone pour l'étudiant ID: {$student->id}" . PHP_EOL;
            $updates['phone'] = Crypt::encryptString($student->phone);
        }
    }

    // Pièce ID
    if ($student->piece_id) {
        try {
            Crypt::decryptString($student->piece_id);
        } catch (DecryptException $e) {
            echo "Chiffrement de la pièce ID pour l'étudiant ID: {$student->id}" . PHP_EOL;
            $updates['piece_id'] = Crypt::encryptString($student->piece_id);
        }
    }

    if (!empty($updates)) {
        DB::table('students')->where('id', $student->id)->update($updates);
    }
}

// Migration des paiements
$payments = DB::table('payments')->get();
foreach ($payments as $payment) {
    if ($payment->phone) {
        try {
            Crypt::decryptString($payment->phone);
        } catch (DecryptException $e) {
            echo "Chiffrement du téléphone pour le paiement ID: {$payment->id}" . PHP_EOL;
            DB::table('payments')->where('id', $payment->id)->update([
                'phone' => Crypt::encryptString($payment->phone)
            ]);
        }
    }
}

echo "Migration terminée avec succès." . PHP_EOL;
