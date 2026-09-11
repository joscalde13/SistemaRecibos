<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BalanceController extends Controller
{
    public function index(Request $request): View
    {
        $status = trim((string) $request->string('status'));

        $receipts = Receipt::query()
            ->with(['person', 'payments'])
            ->withSum('payments', 'amount')
            ->where('status', '!=', Receipt::STATUS_VOID)
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest('issue_date')
            ->latest('id')
            ->paginate(12)
            ->through(function (Receipt $receipt): array {
                $paid = (float) ($receipt->payments_sum_amount ?? 0);
                $pending = max((float) $receipt->total_amount - $paid, 0);
                $lastPaymentDate = $receipt->payments->sortByDesc('payment_date')->first()?->payment_date;

                return [
                    'receipt' => $receipt,
                    'paid' => $paid,
                    'pending' => $pending,
                    'last_payment_date' => $lastPaymentDate,
                ];
            })
            ->withQueryString();

        return view('balances.index', compact('receipts', 'status'));
    }
}
