<?php

namespace App\Imports;

use App\Models\BankStatement;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class BankStatementImport implements ToModel, WithHeadingRow
{
    protected $source;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function model(array $row)
    {
        // Normaliser les clés (minuscules, sans espaces)
        $row = array_change_key_case($row, CASE_LOWER);

        // Extraire les données selon le format du fournisseur
        $date = $this->parseDate($row);
        $reference = $this->parseReference($row);
        $amount = $this->parseAmount($row);

        // Éviter les doublons
        if (
            BankStatement::where('source', $this->source)
                ->where('reference', $reference)
                ->where('date', $date)
                ->exists()
        ) {
            return null;
        }

        return new BankStatement([
            'source' => $this->source,
            'date' => $date,
            'reference' => $reference,
            'amount' => $amount,
            'raw_data' => $row,
            'status' => 'pending',
        ]);
    }

    private function parseDate($row): Carbon
    {
        // Essayer différents formats de date
        $dateFields = ['date', 'date_transaction', 'transaction_date', 'datetime'];

        foreach ($dateFields as $field) {
            if (isset($row[$field]) && !empty($row[$field])) {
                try {
                    return Carbon::parse($row[$field]);
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return now();
    }

    private function parseReference($row): string
    {
        // Essayer différents champs de référence
        $refFields = ['reference', 'ref', 'transaction_id', 'id', 'numero'];

        foreach ($refFields as $field) {
            if (isset($row[$field]) && !empty($row[$field])) {
                return (string) $row[$field];
            }
        }

        return 'REF-' . uniqid();
    }

    private function parseAmount($row): float
    {
        // Essayer différents champs de montant
        $amountFields = ['amount', 'montant', 'total', 'somme', 'credit'];

        foreach ($amountFields as $field) {
            if (isset($row[$field]) && !empty($row[$field])) {
                // Nettoyer le montant (enlever espaces, virgules, etc.)
                $amount = str_replace([' ', ','], ['', '.'], $row[$field]);
                return (float) $amount;
            }
        }

        return 0;
    }
}
