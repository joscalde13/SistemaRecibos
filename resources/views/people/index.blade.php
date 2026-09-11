<x-layouts::app :title="__('Personas')">
    <div class="mx-auto w-full max-w-7xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Personas / Clientes</h1>
            <a href="{{ route('people.create') }}" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Nueva persona</a>
        </div>

        <form method="GET" class="mb-4 rounded-xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex gap-2">
                <input name="search" value="{{ $search }}" placeholder="Buscar por nombre" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                <button class="rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white">Buscar</button>
            </div>
        </form>

        <div class="overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full text-sm">
                <thead class="bg-zinc-50 text-left text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                    <tr>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Identificacion</th>
                        <th class="px-4 py-3">Telefono</th>
                        <th class="px-4 py-3">Fecha registro</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($people as $person)
                        <tr class="border-t border-zinc-100 dark:border-zinc-800">
                            <td class="px-4 py-3 font-medium text-zinc-800 dark:text-zinc-100">{{ $person->full_name }}</td>
                            <td class="px-4 py-3">{{ $person->identifier ?: '-' }}</td>
                            <td class="px-4 py-3">{{ $person->phone ?: '-' }}</td>
                            <td class="px-4 py-3">{{ $person->registered_at?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('people.show', $person) }}" class="text-slate-700 hover:underline">Ver</a>
                                    <a href="{{ route('people.edit', $person) }}" class="text-amber-700 hover:underline">Editar</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-4 py-5 text-zinc-500" colspan="5">No hay personas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $people->links() }}</div>
    </div>
</x-layouts::app>
