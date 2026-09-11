<?php

namespace App\Services;

use App\Models\Receipt;

class ReceiptNumberService
{
    public function next(): string
    {
        $lastReceipt = Receipt::query()
            ->latest('id')
            ->value('receipt_number');

        if (! $lastReceipt) {
            return 'REC-000001';
        }

        $lastSequence = (int) preg_replace('/[^0-9]/', '', $lastReceipt);
        $nextSequence = $lastSequence + 1;

        return 'REC-'.str_pad((string) $nextSequence, 6, '0', STR_PAD_LEFT);
    }
}
