<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReceiptRequest extends FormRequest
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
            'person_id' => ['nullable', 'exists:people,id'],
            'person_name' => ['required_without:person_id', 'string', 'max:255'],
            'person_identifier' => ['nullable', 'string', 'max:80'],
            'issue_date' => ['required', 'date'],
            'concept' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0.01'],
            'abono_amount' => ['nullable', 'numeric', 'min:0'],
            'saldo_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
