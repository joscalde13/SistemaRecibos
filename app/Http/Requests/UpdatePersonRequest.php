<?php

namespace App\Http\Requests;

use App\Models\Person;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonRequest extends FormRequest
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
        /** @var Person $person */
        $person = $this->route('person');

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'identifier' => ['nullable', 'string', 'max:80', Rule::unique('people', 'identifier')->ignore($person->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'notes' => ['nullable', 'string'],
            'registered_at' => ['required', 'date'],
        ];
    }
}
