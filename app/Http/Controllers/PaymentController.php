<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\OfficeSetting;
use App\Models\Payment;
use App\Models\Person;
use App\Models\Receipt;
use App\Services\MoneyToWordsGtService;
use App\Services\PaymentNumberService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentNumberService $paymentNumberService,
        private readonly MoneyToWordsGtService $moneyToWordsGtService,
    ) {
    }

    public function index(Request $request): View
    {
        $personId = $request->integer('person_id');
        $from = $request->string('from')->toString();
        $to = $request->string('to')->toString();

        $payments = Payment::query()
            ->with(['person', 'receipt', 'creator'])
            ->when($personId > 0, fn ($query) => $query->where('person_id', $personId))
            ->when($from !== '', fn ($query) => $query->whereDate('payment_date', '>=', $from))
            ->when($to !== '', fn ($query) => $query->whereDate('payment_date', '<=', $to))
            ->latest('payment_date')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $people = Person::query()->orderBy('full_name')->get(['id', 'full_name']);

        return view('payments.index', compact('payments', 'people', 'personId', 'from', 'to'));
    }

    public function create(Request $request): View
    {
        $receiptId = $request->integer('receipt_id');

        $receipts = Receipt::query()
            ->where('status', '!=', Receipt::STATUS_VOID)
            ->with('person')
            ->withSum('payments', 'amount')
            ->latest('issue_date')
            ->get();

        $selectedReceipt = $receiptId > 0
            ? $receipts->firstWhere('id', $receiptId)
            : null;

        return view('payments.create', [
            'receipts' => $receipts,
            'selectedReceipt' => $selectedReceipt,
            'nextPaymentNumber' => $this->paymentNumberService->next(),
        ]);
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $payment = DB::transaction(function () use ($request): Payment {
            $receipt = Receipt::query()
                ->lockForUpdate()
                ->withSum('payments', 'amount')
                ->findOrFail($request->integer('receipt_id'));

            $paid = (float) ($receipt->payments_sum_amount ?? 0);
            $remaining = (float) $receipt->total_amount - $paid;
            $amount = (float) $request->input('amount');
            $allowOverpayment = (bool) OfficeSetting::query()->value('allow_overpayment');

            if (! $allowOverpayment && $amount > $remaining) {
                abort(422, 'El abono excede el saldo pendiente.');
            }

            $payment = Payment::create([
                'receipt_id' => $receipt->id,
                'person_id' => $receipt->person_id,
                'created_by' => Auth::id(),
                'payment_number' => $this->paymentNumberService->next(),
                'payment_date' => $request->date('payment_date')->toDateString(),
                'amount' => $amount,
                'notes' => $request->input('notes'),
            ]);

            $receipt->refresh();
            $receipt->refreshStatusFromPayments();

            return $payment;
        });

        return redirect()
            ->route('payments.show', $payment)
            ->with('status', 'Abono registrado correctamente.');
    }

    public function show(Payment $payment): View
    {
        $payment->load(['person', 'receipt', 'creator']);
        $receipt = $payment->receipt;
        $receiptPaid = $receipt->paidAmount();
        $receiptBalance = max((float) $receipt->total_amount - $receiptPaid, 0);

        return view('payments.show', [
            'payment' => $payment,
            'receiptPaid' => $receiptPaid,
            'receiptBalance' => $receiptBalance,
            'amountInWords' => $this->moneyToWordsGtService->toWords((float) $payment->amount),
            'officeSetting' => OfficeSetting::query()->first(),
        ]);
    }

    public function pdf(Payment $payment)
    {
        $payment->load(['person', 'receipt', 'creator']);

        $pdf = Pdf::loadView('payments.pdf', [
            'payment' => $payment,
            'amountInWords' => $this->moneyToWordsGtService->toWords((float) $payment->amount),
            'officeSetting' => OfficeSetting::query()->first(),
        ])->setPaper('letter');

        return $pdf->download($payment->payment_number.'.pdf');
    }
}
