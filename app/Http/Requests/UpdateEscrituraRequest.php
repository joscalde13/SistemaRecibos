<?php

namespace App\Http\Requests;

use App\Models\Escritura;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEscrituraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string|Rule>|string>
     */
    public function rules(): array
    {
        /** @var Escritura $escritura */
        $escritura = $this->route('escritura');

        return [
            'client_name' => ['required', 'string', 'max:255'],
            'escritura_number' => ['required', 'string', 'max:100', Rule::unique('escrituras', 'escritura_number')->ignore($escritura->id)],
            'entry_date' => ['required', 'date'],
            'registry_received_date' => ['nullable', 'date', 'after_or_equal:entry_date'],
            'delivery_date' => ['nullable', 'date', 'after_or_equal:entry_date', 'after_or_equal:registry_received_date'],
            'status' => ['required', Rule::in(array_keys(Escritura::statuses()))],
            'notes' => ['nullable', 'string'],
        ];
    }
}