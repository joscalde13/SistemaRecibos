<?php

namespace App\Models;

use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'identifier',
        'phone',
        'address',
        'email',
        'notes',
        'registered_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'registered_at' => 'date',
        ];
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
