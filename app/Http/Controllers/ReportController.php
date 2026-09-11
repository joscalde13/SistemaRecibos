<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Person;
use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfMonth();

        $paymentsInPeriod = Payment::query()
            ->with(['person', 'receipt'])
            ->whereBetween('payment_date', [$from->toDateString(), $to->toDateString()])
            ->orderByDesc('payment_date')
            ->get();

        $receiptsToday = Receipt::query()->whereDate('issue_date', Carbon::today())->count();
        $receiptsMonth = Receipt::query()->whereBetween('issue_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->count();
        $paymentsToday = Payment::query()->whereDate('payment_date', Carbon::today())->sum('amount');
        $paymentsMonth = Payment::query()->whereBetween('payment_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->sum('amount');

        $pendingPeople = Person::query()
            ->whereHas('receipts', fn ($query) => $query->whereIn('status', [Receipt::STATUS_PENDING, Receipt::STATUS_PARTIAL]))
            ->count();

        return view('reports.index', [
            'from' => $from,
            'to' => $to,
            'receiptsToday' => $receiptsToday,
            'receiptsMonth' => $receiptsMonth,
            'paymentsToday' => (float) $paymentsToday,
            'paymentsMonth' => (float) $paymentsMonth,
            'pendingPeople' => $pendingPeople,
            'totalPeriod' => (float) $paymentsInPeriod->sum('amount'),
            'paymentsInPeriod' => $paymentsInPeriod,
        ]);
    }
}
