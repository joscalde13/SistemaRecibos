<x-layouts::app :title="__('Detalle de recibo')">
    <div class="mx-auto w-full max-w-6xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-zinc-500">Recibo de pago</p>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $receipt->receipt_number }}</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('receipts.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Regresar</a>
                <a href="{{ route('receipts.pdf', $receipt) }}" target="_blank" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Ver recibo</a>
                <a href="{{ route('receipts.pdf.download', $receipt) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700">Descargar recibo</a>
                @if($receipt->status !== \App\Models\Receipt::STATUS_VOID)
                    <a href="{{ route('receipts.edit', $receipt) }}" class="rounded-lg border border-amber-300 px-4 py-2 text-sm font-semibold text-amber-700">Editar</a>
                @endif
                <form method="POST" action="{{ route('receipts.destroy', $receipt) }}" onsubmit="return confirm('Deseas eliminar este recibo?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700">Eliminar</button>
                </form>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <p class="text-sm text-zinc-500">Recibimos de</p>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $receipt->person->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-zinc-500">Numero de recibo</p>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $receipt->receipt_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-zinc-500">NIT</p>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $receipt->person->identifier ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-zinc-500">Fecha</p>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $receipt->issue_date?->format('d/m/Y') }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-zinc-500">Concepto</p>
                    <p class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $receipt->concept }}</p>
                </div>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-3">
                <div class="rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                    <p class="text-xs uppercase text-zinc-500">Monto</p>
                    <p class="mt-1 font-semibold">Q{{ number_format((float) $receipt->total_amount, 2) }}</p>
                </div>
                <div class="rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                    <p class="text-xs uppercase text-zinc-500">Cuanto abono</p>
                    <p class="mt-1 font-semibold">Q{{ number_format($paidAmount, 2) }}</p>
                </div>
                <div class="rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                    <p class="text-xs uppercase text-zinc-500">Saldo pendiente</p>
                    <p class="mt-1 font-semibold">Q{{ number_format($balanceAmount, 2) }}</p>
                </div>
            </div>

            <div class="mt-4 rounded-lg border border-zinc-200 p-3 text-sm dark:border-zinc-700">
                <strong>Cantidad en letras:</strong> {{ $amountInWords }}
            </div>

            @if($receipt->notes)
                <div class="mt-4 rounded-lg border border-zinc-200 p-3 text-sm dark:border-zinc-700">
                    <strong>Nota:</strong> {{ $receipt->notes }}
                </div>
            @endif

            @if($receipt->status === \App\Models\Receipt::STATUS_VOID)
                <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">
                    <strong>Recibo anulado.</strong> Motivo: {{ $receipt->void_reason }}
                </div>
            @else
                <form method="POST" action="{{ route('receipts.void', $receipt) }}" class="mt-5 rounded-xl border border-rose-200 bg-rose-50 p-4" onsubmit="return confirm('Deseas anular este recibo?');">
                    @csrf
                    @method('PATCH')
                    <label class="mb-1 block text-sm font-medium text-rose-800">Motivo de anulacion</label>
                    <textarea name="void_reason" rows="2" required class="w-full rounded-lg border border-rose-300 px-3 py-2 text-sm"></textarea>
                    <button class="mt-2 rounded-lg bg-rose-700 px-4 py-2 text-sm font-semibold text-white">Anular recibo</button>
                </form>
            @endif
        </div>

    </div>
</x-layouts::app>
