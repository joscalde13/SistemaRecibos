<x-layouts::app :title="__('Editar recibo')">
    <div class="mx-auto w-full max-w-4xl p-4 md:p-6">
        @include('partials.flash')

        <h1 class="mb-4 text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Editar recibo {{ $receipt->receipt_number }}</h1>

        <form method="POST" action="{{ route('receipts.update', $receipt) }}" class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @method('PUT')
            @include('receipts._form')

            <div class="mt-5 flex gap-3">
                <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Actualizar recibo</button>
                <a href="{{ route('receipts.show', $receipt) }}" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700">Volver</a>
            </div>
        </form>
    </div>
</x-layouts::app>
