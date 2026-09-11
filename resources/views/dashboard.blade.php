<x-layouts::app :title="__('Dashboard')">
    <div class="mx-auto w-full max-w-7xl p-4 md:p-6">
        <div class="mb-6 rounded-2xl border border-zinc-200 bg-linear-to-r from-zinc-900 to-slate-800 p-6 text-white shadow-sm dark:border-zinc-700">
            <p class="text-xs uppercase tracking-[0.2em] text-amber-200">Panel principal</p>
            <h1 class="mt-2 text-2xl font-semibold">OFICINA JURIDICA ALVARO CALDERON S.</h1>
            <p class="mt-2 text-sm text-zinc-200">Sistema simple de recibos listo para imprimir.</p>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('receipts.create') }}" class="rounded-lg bg-amber-400 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-amber-300">Nuevo recibo</a>
                <a href="{{ route('receipts.index') }}" class="rounded-lg border border-zinc-200/30 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">Ver recibos</a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs uppercase text-zinc-500">Recibos emitidos</p>
                <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-zinc-100">{{ number_format($totalReceipts) }}</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs uppercase text-zinc-500">Total en recibos</p>
                <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-zinc-100">Q{{ number_format($totalAmount, 2) }}</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-xs uppercase text-zinc-500">Total recibido</p>
                <p class="mt-2 text-3xl font-bold text-zinc-900 dark:text-zinc-100">Q{{ number_format($totalReceived, 2) }}</p>
            </div>
        </div>

        <div class="mt-6">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Recibos recientes</h2>
                <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 text-left text-zinc-500 dark:border-zinc-700">
                                <th class="py-2">Numero</th>
                                <th class="py-2">Fecha</th>
                                <th class="py-2">Persona</th>
                                <th class="py-2">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReceipts as $receipt)
                                <tr class="border-b border-zinc-100 dark:border-zinc-800">
                                    <td class="py-2"><a href="{{ route('receipts.show', $receipt) }}" class="font-medium text-slate-700 hover:underline dark:text-slate-300">{{ $receipt->receipt_number }}</a></td>
                                    <td class="py-2">{{ $receipt->issue_date?->format('d/m/Y') }}</td>
                                    <td class="py-2">{{ $receipt->person?->full_name }}</td>
                                    <td class="py-2">Q{{ number_format((float) $receipt->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td class="py-2 text-zinc-500" colspan="4">Sin registros</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
