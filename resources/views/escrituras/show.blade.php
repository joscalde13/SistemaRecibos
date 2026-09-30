<x-layouts::app :title="__('Detalle de escritura')">
    <div class="mx-auto w-full max-w-5xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Escritura {{ $escritura->escritura_number }}</h1>
                <p class="mt-1 text-sm text-zinc-500">Cliente: {{ $escritura->client_name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('escrituras.edit', $escritura) }}" class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100">Editar</a>
                <a href="{{ route('escrituras.index') }}" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700">Volver</a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Datos principales</h2>
                <dl class="grid gap-3 text-sm text-zinc-700 dark:text-zinc-200">
                    <div class="flex items-center justify-between gap-4"><dt class="font-medium">Nombre del cliente</dt><dd>{{ $escritura->client_name }}</dd></div>
                    <div class="flex items-center justify-between gap-4"><dt class="font-medium">No. escritura</dt><dd>{{ $escritura->escritura_number }}</dd></div>
                    <div class="flex items-center justify-between gap-4"><dt class="font-medium">Estado</dt><dd>{{ $statuses[$escritura->status] ?? $escritura->status }}</dd></div>
                </dl>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Fechas</h2>
                <dl class="grid gap-3 text-sm text-zinc-700 dark:text-zinc-200">
                    <div class="flex items-center justify-between gap-4"><dt class="font-medium">Fecha de ingreso</dt><dd>{{ $escritura->entry_date?->format('d/m/Y') }}</dd></div>
                    <div class="flex items-center justify-between gap-4"><dt class="font-medium">Fecha recibida del registro</dt><dd>{{ $escritura->registry_received_date?->format('d/m/Y') ?: '-' }}</dd></div>
                    <div class="flex items-center justify-between gap-4"><dt class="font-medium">Fecha de entrega</dt><dd>{{ $escritura->delivery_date?->format('d/m/Y') ?: '-' }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="mt-4 rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-zinc-600 dark:text-zinc-300">Observaciones</h2>
            <p class="text-sm text-zinc-700 dark:text-zinc-200">{{ $escritura->notes ?: 'Sin observaciones.' }}</p>
        </div>
    </div>
</x-layouts::app>