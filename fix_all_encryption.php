<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

echo "Démarrage du nettoyage global du chiffrement..." . PHP_EOL;

function fixTable($tableName, $columns)
{
    $records = DB::table($tableName)->get();
    $fixedCount = 0;

    foreach ($records as $record) {
        $updates = [];
        foreach ($columns as $column) {
            $value = $record->$column;
            if (!$value)
                continue;

            $needsFix = false;
            try {
                Crypt::decryptString($value);
            } catch (DecryptException $e) {
                $needsFix = true;
            }

            if ($needsFix) {
                echo "Fixing {$tableName} ID {$record->id} column {$column}..." . PHP_EOL;
                $updates[$column] = Crypt::encryptString($value);
                $fixedCount++;
            }
        }

        if (!empty($updates)) {
            DB::table($tableName)->where('id', $record->id)->update($updates);
        }
    }
    echo "Table {$tableName} : {$fixedCount} colonnes corrigées." . PHP_EOL;
}

fixTable('students', ['phone', 'piece_id']);
fixTable('payments', ['phone']);

echo "Nettoyage terminé." . PHP_EOL;
