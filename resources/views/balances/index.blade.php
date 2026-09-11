<x-layouts::app :title="__('Saldos pendientes')">
    <div class="mx-auto w-full max-w-7xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Saldos pendientes</h1>
        </div>

        <form method="GET" class="mb-4 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-wrap items-center gap-2">
                <select name="status" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm">
                    <option value="">Todos los estados</option>
                    <option value="pending" @selected($status === 'pending')>Pendiente</option>
                    <option value="partial" @selected($status === 'partial')>Abono parcial</option>
                    <option value="paid" @selected($status === 'paid')>Pagado</option>
                </select>
                <button class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white">Filtrar</button>
            </div>
        </form>

        <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    <tr>
                        <th class="px-4 py-3">Persona</th>
                        <th class="px-4 py-3">Recibo</th>
                        <th class="px-4 py-3">Concepto</th>
                        <th class="px-4 py-3">Monto total</th>
                        <th class="px-4 py-3">Abonado</th>
                        <th class="px-4 py-3">Saldo</th>
                        <th class="px-4 py-3">Ultimo pago</th>
                        <th class="px-4 py-3">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receipts as $row)
                        <tr class="border-t border-zinc-100 dark:border-zinc-800">
                            <td class="px-4 py-3">{{ $row['receipt']->person?->full_name }}</td>
                            <td class="px-4 py-3"><a href="{{ route('receipts.show', $row['receipt']) }}" class="text-slate-700 hover:underline dark:text-slate-300">{{ $row['receipt']->receipt_number }}</a></td>
                            <td class="px-4 py-3">{{ $row['receipt']->concept }}</td>
                            <td class="px-4 py-3">Q{{ number_format((float) $row['receipt']->total_amount, 2) }}</td>
                            <td class="px-4 py-3">Q{{ number_format($row['paid'], 2) }}</td>
                            <td class="px-4 py-3">Q{{ number_format($row['pending'], 2) }}</td>
                            <td class="px-4 py-3">{{ $row['last_payment_date'] ? $row['last_payment_date']->format('d/m/Y') : '-' }}</td>
                            <td class="px-4 py-3">{{ strtoupper($row['receipt']->status) }}</td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-5 text-zinc-500" colspan="8">No hay datos para mostrar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $receipts->links() }}</div>
    </div>
</x-layouts::app>
