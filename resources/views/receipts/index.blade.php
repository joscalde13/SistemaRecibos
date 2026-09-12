<x-layouts::app :title="__('Recibos')">
    <div class="mx-auto w-full max-w-7xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Recibos</h1>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('receipts.export.excel', request()->query()) }}" class="rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-800">Exportar Excel</a>
                <a href="{{ route('receipts.export.pdf', request()->query()) }}" class="rounded-lg border border-red-300 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-100 hover:text-red-800">Exportar PDF</a>
                <a href="{{ route('receipts.create') }}" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Nuevo recibo</a>
            </div>
        </div>

        <form method="GET" class="mb-4 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="grid gap-3 md:grid-cols-[1fr_auto]">
                <input name="search" value="{{ $search }}" placeholder="Nombre, número o concepto" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                <button class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white">Filtrar</button>
            </div>
        </form>

        <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    <tr>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Monto</th>
                        <th class="px-4 py-3">Debe</th>
                        <th class="px-4 py-3">Abono</th>
                        <th class="px-4 py-3">Saldo</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receipts as $receipt)
                        @php
                            $paid = (float) ($receipt->abono_amount ?? 0);
                            $debe = (float) ($receipt->total_amount ?? 0);
                            $saldo = (float) ($receipt->saldo_amount ?? 0);
                        @endphp
                        <tr class="border-t border-zinc-100 dark:border-zinc-800">
                            <td class="px-4 py-3">{{ $receipt->issue_date?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $receipt->person?->full_name }}</td>
                            <td class="px-4 py-3">Q{{ number_format((float) $receipt->total_amount, 2) }}</td>
                            <td class="px-4 py-3">Q{{ number_format($debe, 2) }}</td>
                            <td class="px-4 py-3">Q{{ number_format($paid, 2) }}</td>
                            <td class="px-4 py-3">Q{{ number_format($saldo, 2) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('receipts.show', $receipt) }}" class="text-slate-700 hover:underline">Ver</a>
                                    <a href="{{ route('receipts.edit', $receipt) }}" class="text-amber-700 hover:underline">Editar</a>
                                    <form method="POST" action="{{ route('receipts.destroy', $receipt) }}" onsubmit="return confirm('Deseas eliminar este recibo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-700 hover:underline">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-5 text-zinc-500" colspan="7">No hay recibos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $receipts->links() }}</div>
    </div>
</x-layouts::app>
