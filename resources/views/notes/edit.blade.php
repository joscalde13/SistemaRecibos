<x-layouts::app :title="__('Editar nota')">
    <div class="mx-auto w-full max-w-4xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-5 flex items-end justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Editar nota</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Modifica el texto guardado y conserva el registro original del día.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('notes.update', $note) }}" class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @csrf
            @method('PATCH')

            <div>
                <label for="content" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nota</label>
                <textarea
                    id="content"
                    name="content"
                    rows="14"
                    class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-left text-sm text-zinc-900 shadow-sm outline-none transition placeholder:text-zinc-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-slate-400 dark:focus:ring-slate-900"
                >{{ old('content', $note->content) }}</textarea>

                @error('content')
                    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-5 flex gap-3">
                <button class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                    Guardar cambios
                </button>
                <a href="{{ route('notes.index') }}" class="rounded-xl border border-zinc-300 px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-layouts::app>