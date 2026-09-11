<x-layouts::app :title="__('Nuevo recibo')">
    <div class="mx-auto w-full max-w-4xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-4 rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
            Proximo numero de recibo: <strong>{{ $nextReceiptNumber }}</strong>
        </div>

        <h1 class="mb-4 text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Crear recibo</h1>

        <form method="POST" action="{{ route('receipts.store') }}" class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @include('receipts._form')

            <div class="mt-5 flex gap-3">
                <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Guardar recibo</button>
                <a href="{{ route('receipts.index') }}" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts::app>
