<?php

namespace App\Http\Requests;

use App\Models\OfficeSetting;
use App\Models\Receipt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'receipt_id' => ['required', 'exists:receipts,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $receipt = Receipt::withSum('payments', 'amount')->find($this->integer('receipt_id'));

            if (! $receipt) {
                return;
            }

            if ($receipt->status === Receipt::STATUS_VOID) {
                $validator->errors()->add('receipt_id', 'No se pueden registrar abonos en un recibo anulado.');

                return;
            }

            $paid = (float) ($receipt->payments_sum_amount ?? 0);
            $remaining = (float) $receipt->total_amount - $paid;
            $amount = (float) $this->input('amount');

            $allowOverpayment = (bool) OfficeSetting::query()->value('allow_overpayment');

            if (! $allowOverpayment && $amount > $remaining) {
                $validator->errors()->add('amount', 'El abono excede el saldo pendiente.');
            }
        });
    }
}
