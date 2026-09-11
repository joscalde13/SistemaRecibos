<x-layouts::app :title="__('Reportes')">
    <div class="mx-auto w-full max-w-7xl p-4 md:p-6">
        @include('partials.flash')

        <h1 class="mb-4 text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Reportes</h1>

        <form method="GET" class="mb-4 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="grid gap-3 md:grid-cols-3">
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                <button class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white">Aplicar periodo</button>
            </div>
        </form>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900"><p class="text-xs uppercase text-zinc-500">Recibos del dia</p><p class="mt-2 text-2xl font-bold">{{ $receiptsToday }}</p></div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900"><p class="text-xs uppercase text-zinc-500">Recibos del mes</p><p class="mt-2 text-2xl font-bold">{{ $receiptsMonth }}</p></div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900"><p class="text-xs uppercase text-zinc-500">Abonos del dia</p><p class="mt-2 text-2xl font-bold">Q{{ number_format($paymentsToday, 2) }}</p></div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900"><p class="text-xs uppercase text-zinc-500">Abonos del mes</p><p class="mt-2 text-2xl font-bold">Q{{ number_format($paymentsMonth, 2) }}</p></div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900"><p class="text-xs uppercase text-zinc-500">Personas con saldo pendiente</p><p class="mt-2 text-2xl font-bold">{{ $pendingPeople }}</p></div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900"><p class="text-xs uppercase text-zinc-500">Total recibido en periodo</p><p class="mt-2 text-2xl font-bold">Q{{ number_format($totalPeriod, 2) }}</p></div>
        </div>

        <div class="mt-6 overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    <tr>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Persona</th>
                        <th class="px-4 py-3">Recibo</th>
                        <th class="px-4 py-3">Abono</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentsInPeriod as $payment)
                        <tr class="border-t border-zinc-100 dark:border-zinc-800">
                            <td class="px-4 py-3">{{ $payment->payment_date?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $payment->person?->full_name }}</td>
                            <td class="px-4 py-3">{{ $payment->receipt?->receipt_number }}</td>
                            <td class="px-4 py-3">Q{{ number_format((float) $payment->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-5 text-zinc-500" colspan="4">No hay pagos en el periodo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
