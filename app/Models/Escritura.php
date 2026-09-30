<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escritura extends Model
{
    use HasFactory;

    public const STATUS_PENDING_SEND_REGISTRY = 'pending_send_registry';
    public const STATUS_PENDING_REGISTRY = 'pending_registry';
    public const STATUS_PENDING_DELIVERY = 'pending_delivery';
    public const STATUS_DELIVERED = 'delivered';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'client_name',
        'escritura_number',
        'entry_date',
        'registry_received_date',
        'delivery_date',
        'status',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'registry_received_date' => 'date',
            'delivery_date' => 'date',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING_SEND_REGISTRY => 'Pendiente de mandar al registro',
            self::STATUS_PENDING_REGISTRY => 'Pendiente de venir del registro',
            self::STATUS_PENDING_DELIVERY => 'Pendiente de entregar',
            self::STATUS_DELIVERED => 'Entregada',
        ];
    }
}