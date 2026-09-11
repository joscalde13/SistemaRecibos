<?php

namespace App\Models;

use App\Models\Payment;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receipt extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_PAID = 'paid';
    public const STATUS_VOID = 'void';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'person_id',
        'created_by',
        'receipt_number',
        'issue_date',
        'concept',
        'total_amount',
        'abono_amount',
        'saldo_amount',
        'status',
        'notes',
        'voided_at',
        'voided_by',
        'void_reason',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'voided_at' => 'datetime',
            'total_amount' => 'decimal:2',
            'abono_amount' => 'decimal:2',
            'saldo_amount' => 'decimal:2',
        ];
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'voided_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paidAmount(): float
    {
        return (float) ($this->abono_amount ?? 0);
    }

    public function balanceAmount(): float
    {
        return (float) ($this->saldo_amount ?? ($this->total_amount - $this->paidAmount()));
    }

    public function refreshStatusFromPayments(): void
    {
        if ($this->status === self::STATUS_VOID) {
            return;
        }

        $paid = $this->paidAmount();
        $total = (float) $this->total_amount;

        if ($paid <= 0) {
            $this->status = self::STATUS_PENDING;
        } elseif ($paid < $total) {
            $this->status = self::STATUS_PARTIAL;
        } else {
            $this->status = self::STATUS_PAID;
        }

        $this->save();
    }
}
