<?php

namespace App\Services;

use App\Models\Payment;

class PaymentNumberService
{
    public function next(): string
    {
        $lastPayment = Payment::query()
            ->latest('id')
            ->value('payment_number');

        if (! $lastPayment) {
            return 'PAY-000001';
        }

        $lastSequence = (int) preg_replace('/[^0-9]/', '', $lastPayment);
        $nextSequence = $lastSequence + 1;

        return 'PAY-'.str_pad((string) $nextSequence, 6, '0', STR_PAD_LEFT);
    }
}
