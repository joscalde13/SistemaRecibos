<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Receipt;

class DashboardController extends Controller
{
    public function index()
    {
        $receipts = Receipt::query()->where('status', '!=', Receipt::STATUS_VOID)->get();
        $totalRecibido = $receipts->sum(fn (Receipt $receipt): float => (float) $receipt->abono_amount);
        $totalPendiente = $receipts->sum(fn (Receipt $receipt): float => (float) $receipt->saldo_amount);

        $personasConDeuda = Person::query()
            ->whereHas('receipts', function ($query): void {
                $query->where('status', '!=', Receipt::STATUS_VOID)
                    ->where('saldo_amount', '>', 0);
            })
            ->count();

        $deudores = Person::query()
            ->withSum(['receipts as pending_amount' => function ($query): void {
                $query->where('status', '!=', Receipt::STATUS_VOID)
                    ->where('saldo_amount', '>', 0);
            }], 'saldo_amount')
            ->get()
            ->filter(fn (Person $person): bool => (float) ($person->pending_amount ?? 0) > 0)
            ->sortByDesc('pending_amount')
            ->values();

        $currentMonth = now()->startOfMonth();
        $previousMonth = now()->copy()->subMonth()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();
        $previousMonthEnd = $previousMonth->copy()->endOfMonth();

        $currentMonthReceipts = Receipt::query()
            ->where('status', '!=', Receipt::STATUS_VOID)
            ->whereBetween('issue_date', [$currentMonth->toDateString(), $currentMonthEnd->toDateString()])
            ->get();

        $previousMonthReceipts = Receipt::query()
            ->where('status', '!=', Receipt::STATUS_VOID)
            ->whereBetween('issue_date', [$previousMonth->toDateString(), $previousMonthEnd->toDateString()])
            ->get();

        $comparisonMetrics = [
            [
                'label' => 'Recibos',
                'current' => $currentMonthReceipts->count(),
                'previous' => $previousMonthReceipts->count(),
            ],
            [
                'label' => 'Cobros',
                'current' => (float) $currentMonthReceipts->sum('abono_amount'),
                'previous' => (float) $previousMonthReceipts->sum('abono_amount'),
            ],
            [
                'label' => 'Saldo',
                'current' => (float) $currentMonthReceipts->sum('saldo_amount'),
                'previous' => (float) $previousMonthReceipts->sum('saldo_amount'),
            ],
        ];

        $comparisonMetrics = array_map(function (array $metric): array {
            $current = (float) $metric['current'];
            $previous = (float) $metric['previous'];

            $metric['variance'] = $previous > 0
                ? round((($current - $previous) / $previous) * 100, 1)
                : 0;

            return $metric;
        }, $comparisonMetrics);

        return view('dashboard', [
            'totalReceipts' => Receipt::count(),
            'totalAmount' => (float) $receipts->sum('total_amount'),
            'totalReceived' => (float) $totalRecibido,
            'totalPending' => (float) $totalPendiente,
            'peopleWithDebt' => $personasConDeuda,
            'debtors' => $deudores,
            'recentReceipts' => Receipt::with('person')->latest('issue_date')->latest('id')->limit(8)->get(),
            'comparisonMetrics' => $comparisonMetrics,
            'comparisonMax' => max(1, ...array_map(fn ($metric) => max((float) $metric['current'], (float) $metric['previous']), $comparisonMetrics)),
        ]);
    }
}
