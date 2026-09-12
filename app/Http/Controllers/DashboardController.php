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

        return view('dashboard', [
            'totalReceipts' => Receipt::count(),
            'totalAmount' => (float) $receipts->sum('total_amount'),
            'totalReceived' => (float) $totalRecibido,
            'totalPending' => (float) $totalPendiente,
            'peopleWithDebt' => $personasConDeuda,
            'debtors' => $deudores,
            'recentReceipts' => Receipt::with('person')->latest('issue_date')->latest('id')->limit(8)->get(),
        ]);
    }
}
