<x-layouts::app :title="__('Dashboard')">
    <div class="mx-auto w-full max-w-7xl p-4 md:p-6">
        <div class="mb-6 rounded-2xl border border-zinc-200 bg-linear-to-r from-zinc-900 to-slate-800 p-6 text-white shadow-sm dark:border-zinc-700">
            <p class="text-xs uppercase tracking-[0.2em] text-amber-200">Panel principal</p>
            <h1 class="mt-2 text-2xl font-semibold">OFICINA JURIDICA Lic. ALVARO CALDERON S.</h1>
            <p class="mt-2 text-sm text-zinc-200">Sistema simple de recibos listo para imprimir.</p>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('receipts.create') }}" class="rounded-lg bg-amber-400 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-amber-300">Nuevo recibo</a>
                <a href="{{ route('receipts.index') }}" class="rounded-lg border border-zinc-200/30 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">Ver recibos</a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5">
            <div class="min-w-0 overflow-hidden rounded-xl border border-zinc-200 bg-white p-3 shadow-sm sm:p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-[10px] font-medium uppercase tracking-wide text-zinc-500 sm:text-xs">Recibos emitidos</p>
                <p class="mt-2 text-2xl font-bold leading-tight text-zinc-900 sm:text-3xl dark:text-zinc-100">{{ number_format($totalReceipts) }}</p>
            </div>
            <div class="min-w-0 overflow-hidden rounded-xl border border-zinc-200 bg-white p-3 shadow-sm sm:p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-[10px] font-medium uppercase tracking-wide text-zinc-500 sm:text-xs">Total en recibos</p>
                <p class="mt-2 text-2xl font-bold leading-tight text-zinc-900 sm:text-3xl dark:text-zinc-100">Q{{ number_format($totalAmount, 2) }}</p>
            </div>
            <div class="min-w-0 overflow-hidden rounded-xl border border-zinc-200 bg-white p-3 shadow-sm sm:p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-[10px] font-medium uppercase tracking-wide text-zinc-500 sm:text-xs">Total recibido</p>
                <p class="mt-2 text-2xl font-bold leading-tight text-zinc-900 sm:text-3xl dark:text-zinc-100">Q{{ number_format($totalReceived, 2) }}</p>
            </div>
            <div class="min-w-0 overflow-hidden rounded-xl border border-zinc-200 bg-white p-3 shadow-sm sm:p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-[10px] font-medium uppercase tracking-wide text-zinc-500 sm:text-xs">Total pendiente</p>
                <p class="mt-2 text-2xl font-bold leading-tight text-zinc-900 sm:text-3xl dark:text-zinc-100">Q{{ number_format($totalPending, 2) }}</p>
            </div>
            <div class="min-w-0 overflow-hidden rounded-xl border border-zinc-200 bg-white p-3 shadow-sm sm:p-4 dark:border-zinc-700 dark:bg-zinc-900">
                <p class="text-[10px] font-medium uppercase tracking-wide text-zinc-500 sm:text-xs">Personas con deuda</p>
                <p class="mt-2 text-2xl font-bold leading-tight text-zinc-900 sm:text-3xl dark:text-zinc-100">{{ number_format($peopleWithDebt) }}</p>
            </div>

            <div class="min-w-0 overflow-hidden rounded-xl border border-zinc-200 bg-white p-3 shadow-sm sm:p-4 dark:border-zinc-700 dark:bg-zinc-900 sm:col-span-2 xl:col-span-5">
                <p class="text-[10px] font-medium uppercase tracking-wide text-zinc-500 sm:text-xs">Personas que deben</p>
                @if($debtors->isNotEmpty())
                    <div class="mt-3 space-y-2">
                        @foreach($debtors as $debtor)
                            <div class="flex items-center justify-between gap-2 rounded-lg bg-zinc-50 px-2 py-1.5 text-xs text-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                <span class="truncate font-medium">{{ $debtor->full_name }}</span>
                                <span class="shrink-0 font-semibold text-amber-700 dark:text-amber-300">Q{{ number_format((float) $debtor->pending_amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-3 text-sm text-zinc-500">No hay personas con deuda.</p>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Recibos recientes</h2>
                <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 text-left text-zinc-500 dark:border-zinc-700">
                                <th class="px-5 py-3">Fecha</th>
                                <th class="px-5 py-3">Persona</th>
                                <th class="px-5 py-3">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReceipts as $receipt)
                                <tr class="border-b border-zinc-100 dark:border-zinc-800">
                                    <td class="px-5 py-3 whitespace-nowrap">{{ $receipt->issue_date?->format('d/m/Y') }}</td>
                                    <td class="px-5 py-3 whitespace-nowrap">{{ $receipt->person?->full_name }}</td>
                                    <td class="px-5 py-3 whitespace-nowrap">Q{{ number_format((float) $receipt->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td class="px-5 py-3 text-zinc-500" colspan="3">Sin registros</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
