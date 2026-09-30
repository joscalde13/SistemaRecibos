<x-layouts::app :title="__('Escrituras')">
    <div class="mx-auto w-full max-w-7xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Escrituras</h1>
            <a href="{{ route('escrituras.create') }}" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Nueva escritura</a>
        </div>

        <div class="mb-4 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-rose-700">Pendiente de mandar al registro</p>
                <p class="mt-2 text-2xl font-bold text-rose-900">{{ $summary['pending_send_registry'] }}</p>
            </div>
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-amber-700">Pendiente del registro</p>
                <p class="mt-2 text-2xl font-bold text-amber-900">{{ $summary['pending_registry'] }}</p>
            </div>
            <div class="rounded-xl border border-sky-200 bg-sky-50 p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-sky-700">Pendiente de entregar</p>
                <p class="mt-2 text-2xl font-bold text-sky-900">{{ $summary['pending_delivery'] }}</p>
            </div>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-emerald-700">Entregadas</p>
                <p class="mt-2 text-2xl font-bold text-emerald-900">{{ $summary['delivered'] }}</p>
            </div>
        </div>

        <form method="GET" class="mb-4 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="grid gap-3 md:grid-cols-[1fr_240px_auto]">
                <input name="search" value="{{ $search }}" placeholder="Buscar por cliente o número" class="rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                <select name="status" class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700">
                    <option value="">Todos los estados</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white">Filtrar</button>
            </div>
        </form>

        <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    <tr>
                        <th class="px-4 py-3">Cliente</th>
                        <th class="px-4 py-3">No. escritura</th>
                        <th class="px-4 py-3">Fecha de creación de la escritura</th>
                        <th class="px-4 py-3">Fecha recibida del registro</th>
                        <th class="px-4 py-3">Fecha de entrega</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($escrituras as $escritura)
                        <tr class="border-t border-zinc-100 dark:border-zinc-800">
                            <td class="px-4 py-3 font-medium text-zinc-800 dark:text-zinc-100">{{ $escritura->client_name }}</td>
                            <td class="px-4 py-3">{{ $escritura->escritura_number }}</td>
                            <td class="px-4 py-3">{{ $escritura->entry_date?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $escritura->registry_received_date?->format('d/m/Y') ?: '-' }}</td>
                            <td class="px-4 py-3">{{ $escritura->delivery_date?->format('d/m/Y') ?: '-' }}</td>
                            <td class="px-4 py-3">
                                @php($label = $statuses[$escritura->status] ?? $escritura->status)
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $escritura->status === \App\Models\Escritura::STATUS_DELIVERED ? 'bg-emerald-100 text-emerald-700' : ($escritura->status === \App\Models\Escritura::STATUS_PENDING_DELIVERY ? 'bg-sky-100 text-sky-700' : ($escritura->status === \App\Models\Escritura::STATUS_PENDING_REGISTRY ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700')) }}">{{ $label }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('escrituras.edit', $escritura) }}" class="text-amber-700 hover:underline">Editar</a>
                                    <form method="POST" action="{{ route('escrituras.destroy', $escritura) }}" onsubmit="return confirm('Deseas eliminar esta escritura?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-700 hover:underline">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-5 text-zinc-500" colspan="7">No hay escrituras registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $escrituras->links() }}</div>
    </div>
</x-layouts::app>