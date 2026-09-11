<x-layouts::app :title="__('Detalle de persona')">
    <div class="mx-auto w-full max-w-7xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $person->full_name }}</h1>
                <p class="text-sm text-zinc-500">{{ $person->identifier ?: 'Sin identificacion registrada' }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('receipts.create') }}" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Crear recibo</a>
                <a href="{{ route('people.edit', $person) }}" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700">Editar</a>
                <form method="POST" action="{{ route('people.destroy', $person) }}" onsubmit="return confirm('Deseas eliminar esta persona?');">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-lg border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700">Eliminar</button>
                </form>
            </div>
        </div>

        <div class="mb-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs uppercase text-zinc-500">Monto total</p>
                <p class="mt-2 text-2xl font-bold">Q{{ number_format($totalAmount, 2) }}</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs uppercase text-zinc-500">Total abonado</p>
                <p class="mt-2 text-2xl font-bold">Q{{ number_format($totalPaid, 2) }}</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs uppercase text-zinc-500">Saldo pendiente</p>
                <p class="mt-2 text-2xl font-bold">Q{{ number_format($totalPending, 2) }}</p>
            </div>
        </div>

        <div class="grid gap-4 xl:grid-cols-2">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Historial de recibos</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 text-left text-zinc-500 dark:border-zinc-700">
                                <th class="py-2">Numero</th>
                                <th class="py-2">Monto</th>
                                <th class="py-2">Abonado</th>
                                <th class="py-2">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($receipts as $receipt)
                                <tr class="border-b border-zinc-100 dark:border-zinc-800">
                                    <td class="py-2"><a href="{{ route('receipts.show', $receipt) }}" class="font-medium text-slate-700 hover:underline dark:text-slate-300">{{ $receipt->receipt_number }}</a></td>
                                    <td class="py-2">Q{{ number_format((float) $receipt->total_amount, 2) }}</td>
                                    <td class="py-2">Q{{ number_format((float) ($receipt->payments_sum_amount ?? 0), 2) }}</td>
                                    <td class="py-2">{{ strtoupper($receipt->status) }}</td>
                                </tr>
                            @empty
                                <tr><td class="py-2 text-zinc-500" colspan="4">Sin recibos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $receipts->links() }}</div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Historial de abonos</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 text-left text-zinc-500 dark:border-zinc-700">
                                <th class="py-2">Numero</th>
                                <th class="py-2">Fecha</th>
                                <th class="py-2">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr class="border-b border-zinc-100 dark:border-zinc-800">
                                    <td class="py-2"><a href="{{ route('payments.show', $payment) }}" class="font-medium text-slate-700 hover:underline dark:text-slate-300">{{ $payment->payment_number }}</a></td>
                                    <td class="py-2">{{ $payment->payment_date?->format('d/m/Y') }}</td>
                                    <td class="py-2">Q{{ number_format((float) $payment->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td class="py-2 text-zinc-500" colspan="3">Sin abonos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $payments->links() }}</div>
            </div>
        </div>
    </div>
</x-layouts::app>
