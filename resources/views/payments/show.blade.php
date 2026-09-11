<x-layouts::app :title="__('Detalle de abono')">
    <div class="mx-auto w-full max-w-5xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-zinc-500">Comprobante de abono</p>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $payment->payment_number }}</h1>
            </div>
            <a href="{{ route('payments.pdf', $payment) }}" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Descargar PDF</a>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <p class="text-sm text-zinc-500">Persona</p>
                    <p class="font-semibold">{{ $payment->person->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-zinc-500">Recibo asociado</p>
                    <p class="font-semibold">{{ $payment->receipt->receipt_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-zinc-500">Fecha</p>
                    <p class="font-semibold">{{ $payment->payment_date?->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-zinc-500">Usuario</p>
                    <p class="font-semibold">{{ $payment->creator?->name ?: '-' }}</p>
                </div>
            </div>

            <div class="mt-5 grid gap-4 md:grid-cols-3">
                <div class="rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                    <p class="text-xs uppercase text-zinc-500">Abono</p>
                    <p class="mt-1 text-xl font-bold">Q{{ number_format((float) $payment->amount, 2) }}</p>
                </div>
                <div class="rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                    <p class="text-xs uppercase text-zinc-500">Total abonado en recibo</p>
                    <p class="mt-1 text-xl font-bold">Q{{ number_format($receiptPaid, 2) }}</p>
                </div>
                <div class="rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                    <p class="text-xs uppercase text-zinc-500">Saldo pendiente</p>
                    <p class="mt-1 text-xl font-bold">Q{{ number_format($receiptBalance, 2) }}</p>
                </div>
            </div>

            <div class="mt-4 rounded-lg border border-zinc-200 p-3 text-sm dark:border-zinc-700">
                <strong>Cantidad en letras:</strong> {{ $amountInWords }}
            </div>

            @if($payment->notes)
                <div class="mt-4 rounded-lg border border-zinc-200 p-3 text-sm dark:border-zinc-700">
                    <strong>Nota:</strong> {{ $payment->notes }}
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
