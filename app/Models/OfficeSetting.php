<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeSetting extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'office_name',
        'office_address',
        'office_phone',
        'office_email',
        'notary_name',
        'notary_license',
        'receipt_footer',
        'logo_path',
        'signature_path',
        'show_signature',
        'allow_overpayment',
        'use_digital_signature_provider',
        'digital_signature_provider',
        'digital_signature_notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'show_signature' => 'boolean',
            'allow_overpayment' => 'boolean',
            'use_digital_signature_provider' => 'boolean',
        ];
    }
}
