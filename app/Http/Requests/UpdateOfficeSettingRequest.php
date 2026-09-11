<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfficeSettingRequest extends FormRequest
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
            'office_name' => ['required', 'string', 'max:255'],
            'office_address' => ['nullable', 'string', 'max:255'],
            'office_phone' => ['nullable', 'string', 'max:50'],
            'office_email' => ['nullable', 'email', 'max:255'],
            'notary_name' => ['required', 'string', 'max:255'],
            'notary_license' => ['nullable', 'string', 'max:100'],
            'receipt_footer' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'signature' => ['nullable', 'image', 'max:2048'],
            'show_signature' => ['nullable', 'boolean'],
            'allow_overpayment' => ['nullable', 'boolean'],
            'use_digital_signature_provider' => ['nullable', 'boolean'],
            'digital_signature_provider' => ['nullable', 'string', 'max:120'],
            'digital_signature_notes' => ['nullable', 'string'],
        ];
    }
}
