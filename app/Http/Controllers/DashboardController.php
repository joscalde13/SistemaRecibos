<?php

namespace App\Http\Controllers;

use App\Models\Receipt;

class DashboardController extends Controller
{
    public function index()
    {
        $receipts = Receipt::query()->where('status', '!=', Receipt::STATUS_VOID)->get();
        $totalRecibido = $receipts->sum(fn (Receipt $receipt): float => (float) $receipt->abono_amount);

        return view('dashboard', [
            'totalReceipts' => Receipt::count(),
            'totalAmount' => (float) $receipts->sum('total_amount'),
            'totalReceived' => (float) $totalRecibido,
            'recentReceipts' => Receipt::with('person')->latest('issue_date')->latest('id')->limit(8)->get(),
        ]);
    }
}
