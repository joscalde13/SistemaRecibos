<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReceiptRequest;
use App\Http\Requests\UpdateReceiptRequest;
use App\Models\OfficeSetting;
use App\Models\Person;
use App\Models\Receipt;
use App\Services\MoneyToWordsGtService;
use App\Services\ReceiptNumberService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    public function __construct(
        private readonly ReceiptNumberService $receiptNumberService,
        private readonly MoneyToWordsGtService $moneyToWordsGtService,
    ) {
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $from = $request->string('from')->toString();
        $to = $request->string('to')->toString();

        $receipts = Receipt::query()
            ->with('person')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('receipt_number', 'like', "%{$search}%")
                        ->orWhere('concept', 'like', "%{$search}%")
                        ->orWhereHas('person', function ($personQuery) use ($search): void {
                            $personQuery->where('full_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($from !== '', fn ($query) => $query->whereDate('issue_date', '>=', $from))
            ->when($to !== '', fn ($query) => $query->whereDate('issue_date', '<=', $to))
            ->latest('issue_date')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('receipts.index', compact('receipts', 'search', 'from', 'to'));
    }

    public function create(): View
    {
        $people = Person::query()
            ->select(['id', 'full_name', 'identifier', 'phone', 'address', 'email'])
            ->with(['receipts' => function ($query): void {
                $query->where('status', '!=', Receipt::STATUS_VOID)
                    ->orderByDesc('issue_date')
                    ->orderByDesc('id')
                    ->limit(1);
            }])
            ->withSum(['receipts as total_receipts_abono' => function ($query): void {
                $query->where('status', '!=', Receipt::STATUS_VOID);
            }], 'abono_amount')
            ->orderBy('full_name')
            ->get();

        return view('receipts.create', [
            'people' => $people,
            'nextReceiptNumber' => $this->receiptNumberService->next(),
        ]);
    }

    public function store(StoreReceiptRequest $request): RedirectResponse
    {
        $receipt = DB::transaction(function () use ($request): Receipt {
            $number = $this->receiptNumberService->next();
            $data = $request->validated();

            $person = null;

            if (! empty($data['person_id'])) {
                $person = Person::findOrFail((int) $data['person_id']);

                if (! $person->identifier && ! empty($data['person_identifier'])) {
                    $person->update(['identifier' => $data['person_identifier']]);
                }
            } else {
                $person = Person::firstOrCreate(
                    ['full_name' => $data['person_name']],
                    [
                        'identifier' => $data['person_identifier'] ?? null,
                        'registered_at' => now()->toDateString(),
                    ],
                );

                if (! $person->identifier && ! empty($data['person_identifier'])) {
                    $person->update(['identifier' => $data['person_identifier']]);
                }
            }

            return Receipt::create([
                'person_id' => $person->id,
                'issue_date' => $data['issue_date'],
                'concept' => $data['concept'],
                'total_amount' => $data['total_amount'],
                'abono_amount' => $data['abono_amount'] ?? 0,
                'saldo_amount' => $data['saldo_amount'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
                'receipt_number' => $number,
                'status' => Receipt::STATUS_PENDING,
            ]);
        });

        $receipt->refreshStatusFromPayments();

        return redirect()
            ->route('receipts.show', $receipt)
            ->with('status', 'Recibo creado correctamente.');
    }

    public function show(Receipt $receipt): View
    {
        $receipt->load(['person', 'creator']);

        $paid = (float) ($receipt->abono_amount ?? 0);
        $balance = (float) ($receipt->saldo_amount ?? 0);
        $officeSetting = OfficeSetting::query()->first();

        return view('receipts.show', [
            'receipt' => $receipt,
            'paidAmount' => $paid,
            'balanceAmount' => $balance,
            'amountInWords' => $this->moneyToWordsGtService->toWords((float) $receipt->total_amount),
            'officeSetting' => $officeSetting,
        ]);
    }

    public function edit(Receipt $receipt): View|RedirectResponse
    {
        if ($receipt->status === Receipt::STATUS_VOID) {
            return back()->withErrors([
                'receipt' => 'No se puede editar un recibo anulado.',
            ]);
        }

        $people = Person::query()
            ->select(['id', 'full_name', 'identifier', 'phone', 'address', 'email'])
            ->with(['receipts' => function ($query): void {
                $query->where('status', '!=', Receipt::STATUS_VOID)
                    ->orderByDesc('issue_date')
                    ->orderByDesc('id')
                    ->limit(1);
            }])
            ->withSum(['receipts as total_receipts_abono' => function ($query): void {
                $query->where('status', '!=', Receipt::STATUS_VOID);
            }], 'abono_amount')
            ->orderBy('full_name')
            ->get();

        return view('receipts.edit', compact('receipt', 'people'));
    }

    public function update(UpdateReceiptRequest $request, Receipt $receipt): RedirectResponse
    {
        if ($receipt->status === Receipt::STATUS_VOID) {
            return back()->withErrors([
                'receipt' => 'No se puede editar un recibo anulado.',
            ]);
        }

        $data = $request->validated();

        $person = null;

        if (! empty($data['person_id'])) {
            $person = Person::findOrFail((int) $data['person_id']);

            if (! $person->identifier && ! empty($data['person_identifier'])) {
                $person->update(['identifier' => $data['person_identifier']]);
            }
        } else {
            $person = Person::firstOrCreate(
                ['full_name' => $data['person_name']],
                [
                    'identifier' => $data['person_identifier'] ?? null,
                    'registered_at' => now()->toDateString(),
                ],
            );

            if (! $person->identifier && ! empty($data['person_identifier'])) {
                $person->update(['identifier' => $data['person_identifier']]);
            }
        }

        $receipt->update([
            'person_id' => $person->id,
            'issue_date' => $data['issue_date'],
            'concept' => $data['concept'],
            'total_amount' => $data['total_amount'],
            'abono_amount' => $data['abono_amount'] ?? 0,
            'saldo_amount' => $data['saldo_amount'] ?? 0,
            'notes' => $data['notes'] ?? null,
        ]);
        $receipt->refreshStatusFromPayments();

        return redirect()
            ->route('receipts.show', $receipt)
            ->with('status', 'Recibo actualizado correctamente.');
    }

    public function destroy(Receipt $receipt): RedirectResponse
    {
        if ($receipt->payments()->exists()) {
            return back()->withErrors([
                'receipt' => 'No se puede eliminar un recibo con pagos relacionados.',
            ]);
        }

        $receipt->delete();

        return redirect()
            ->route('receipts.index')
            ->with('status', 'Recibo eliminado correctamente.');
    }

    public function void(Request $request, Receipt $receipt): RedirectResponse
    {
        $validated = $request->validate([
            'void_reason' => ['required', 'string', 'max:500'],
        ]);

        if ($receipt->status === Receipt::STATUS_VOID) {
            return back()->withErrors([
                'receipt' => 'El recibo ya fue anulado.',
            ]);
        }

        $receipt->update([
            'status' => Receipt::STATUS_VOID,
            'void_reason' => $validated['void_reason'],
            'voided_at' => now(),
            'voided_by' => Auth::id(),
        ]);

        return redirect()
            ->route('receipts.show', $receipt)
            ->with('status', 'Recibo anulado correctamente.');
    }

    public function pdf(Receipt $receipt)
    {
        $receipt->load(['person', 'creator']);
        $paid = (float) ($receipt->abono_amount ?? 0);
        $balance = (float) ($receipt->saldo_amount ?? 0);
        $officeSetting = OfficeSetting::query()->first();

        $pdf = Pdf::loadView('receipts.pdf', [
            'receipt' => $receipt,
            'paidAmount' => $paid,
            'balanceAmount' => $balance,
            'amountInWords' => $this->moneyToWordsGtService->toWords((float) $receipt->total_amount),
            'officeSetting' => $officeSetting,
        ])->setPaper('letter');

        return $pdf->stream($receipt->receipt_number.'.pdf');
    }

    public function pdfDownload(Receipt $receipt)
    {
        $receipt->load(['person', 'creator']);
        $paid = (float) ($receipt->abono_amount ?? 0);
        $balance = (float) ($receipt->saldo_amount ?? 0);
        $officeSetting = OfficeSetting::query()->first();

        $pdf = Pdf::loadView('receipts.pdf', [
            'receipt' => $receipt,
            'paidAmount' => $paid,
            'balanceAmount' => $balance,
            'amountInWords' => $this->moneyToWordsGtService->toWords((float) $receipt->total_amount),
            'officeSetting' => $officeSetting,
        ])->setPaper('letter');

        return $pdf->download($receipt->receipt_number.'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $search = trim((string) $request->string('search'));
        $from = $request->string('from')->toString();
        $to = $request->string('to')->toString();

        $receipts = Receipt::query()
            ->with('person')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('receipt_number', 'like', "%{$search}%")
                        ->orWhere('concept', 'like', "%{$search}%")
                        ->orWhereHas('person', function ($personQuery) use ($search): void {
                            $personQuery->where('full_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($from !== '', fn ($query) => $query->whereDate('issue_date', '>=', $from))
            ->when($to !== '', fn ($query) => $query->whereDate('issue_date', '<=', $to))
            ->latest('issue_date')
            ->latest('id')
            ->get();

        $filename = 'recibos-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($receipts): void {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['Fecha', 'Nombre', 'Monto', 'Debe', 'Abono', 'Saldo']);

            foreach ($receipts as $receipt) {
                $paid = (float) ($receipt->abono_amount ?? 0);
                $debe = (float) ($receipt->total_amount ?? 0);
                $saldo = (float) ($receipt->saldo_amount ?? 0);

                fputcsv($handle, [
                    $receipt->issue_date?->format('d/m/Y') ?? '',
                    $receipt->person?->full_name ?? '',
                    number_format((float) $receipt->total_amount, 2, '.', ''),
                    number_format($debe, 2, '.', ''),
                    number_format($paid, 2, '.', ''),
                    number_format($saldo, 2, '.', ''),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $search = trim((string) $request->string('search'));
        $from = $request->string('from')->toString();
        $to = $request->string('to')->toString();

        $receipts = Receipt::query()
            ->with('person')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('receipt_number', 'like', "%{$search}%")
                        ->orWhere('concept', 'like', "%{$search}%")
                        ->orWhereHas('person', function ($personQuery) use ($search): void {
                            $personQuery->where('full_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($from !== '', fn ($query) => $query->whereDate('issue_date', '>=', $from))
            ->when($to !== '', fn ($query) => $query->whereDate('issue_date', '<=', $to))
            ->latest('issue_date')
            ->latest('id')
            ->get();

        $pdf = Pdf::loadView('receipts.export-pdf', [
            'receipts' => $receipts,
        ])->setPaper('letter', 'landscape');

        return $pdf->download('reporte-de-recibos.pdf');
    }
}
