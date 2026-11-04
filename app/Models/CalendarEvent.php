<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = ['created_by', 'title', 'note', 'event_date', 'event_time', 'remind_days_before'];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'remind_days_before' => 'integer',
        ];
    }

    /** Eventos de hoy en adelante cuya ventana de aviso ya inició. */
    public function scopeDueSoon(Builder $query): Builder
    {
        $driver = $query->getConnection()->getDriverName();

        return $driver === 'sqlite'
            ? $query->whereDate('event_date', '>=', today())
                ->whereRaw("date(event_date, '-' || remind_days_before || ' days') <= ?", [today()->toDateString()])
            : $query->whereDate('event_date', '>=', today())
                ->whereRaw('DATE_SUB(event_date, INTERVAL remind_days_before DAY) <= ?', [today()->toDateString()]);
    }
}
