<x-layouts::app :title="__('Abonos')">
    <div class="mx-auto w-full max-w-7xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Abonos / Pagos</h1>
            <a href="{{ route('payments.create') }}" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Registrar abono</a>
        </div>

        <form method="GET" class="mb-4 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="grid gap-3 md:grid-cols-4">
                <select name="person_id" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm">
                    <option value="">Todas las personas</option>
                    @foreach($people as $person)
                        <option value="{{ $person->id }}" @selected($personId == $person->id)>{{ $person->full_name }}</option>
                    @endforeach
                </select>
                <input type="date" name="from" value="{{ $from }}" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                <input type="date" name="to" value="{{ $to }}" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                <button class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white">Filtrar</button>
            </div>
        </form>

        <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    <tr>
                        <th class="px-4 py-3">Numero</th>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Persona</th>
                        <th class="px-4 py-3">Recibo</th>
                        <th class="px-4 py-3">Monto</th>
                        <th class="px-4 py-3">Usuario</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr class="border-t border-zinc-100 dark:border-zinc-800">
                            <td class="px-4 py-3 font-medium text-slate-700 dark:text-slate-300">{{ $payment->payment_number }}</td>
                            <td class="px-4 py-3">{{ $payment->payment_date?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $payment->person?->full_name }}</td>
                            <td class="px-4 py-3">{{ $payment->receipt?->receipt_number }}</td>
                            <td class="px-4 py-3">Q{{ number_format((float) $payment->amount, 2) }}</td>
                            <td class="px-4 py-3">{{ $payment->creator?->name ?: '-' }}</td>
                            <td class="px-4 py-3"><a href="{{ route('payments.show', $payment) }}" class="text-slate-700 hover:underline">Ver</a></td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-5 text-zinc-500" colspan="7">No hay abonos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $payments->links() }}</div>
    </div>
</x-layouts::app>
