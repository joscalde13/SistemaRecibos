<x-layouts::app :title="__('Nueva persona')">
    <div class="mx-auto w-full max-w-4xl p-4 md:p-6">
        @include('partials.flash')

        <h1 class="mb-4 text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Registrar persona</h1>

        <form method="POST" action="{{ route('people.store') }}" class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @include('people._form')

            <div class="mt-5 flex gap-3">
                <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Guardar</button>
                <a href="{{ route('people.index') }}" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts::app>
