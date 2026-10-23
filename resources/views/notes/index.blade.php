<x-layouts::app :title="__('Notas del día')">
    <div class="mx-auto w-full max-w-7xl p-4 md:p-6">
        @include('partials.flash')

       

        <section class="mb-6 rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-3">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Nueva nota</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Escribe aquí los datos tal como los recibes: nombre, teléfono, dirección o cualquier detalle.</p>
            </div>

            <form method="POST" action="{{ route('notes.store') }}" class="space-y-3">
                @csrf

                <div>
                    <label for="content" class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Nota</label>
                    <textarea
                        id="content"
                        name="content"
                        rows="7"
                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-left text-sm text-zinc-900 shadow-sm outline-none transition placeholder:text-zinc-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-slate-400 dark:focus:ring-slate-900"
                    >{{ old('content') }}</textarea>

                    @error('content')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <button class="inline-flex w-full items-center justify-center rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                    Guardar nota
                </button>
            </form>
        </section>

        <section class="space-y-4">
                <form method="GET" class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="grid gap-3 md:grid-cols-[1fr_220px_auto_auto_auto_auto]">
                        <input
                            name="search"
                            value="{{ $search }}"
                            placeholder="Buscar en las notas"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm text-zinc-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-slate-400 dark:focus:ring-slate-900"
                        />
                        <input
                            type="date"
                            name="date"
                            value="{{ $date }}"
                            class="w-full rounded-xl border border-zinc-300 px-4 py-2.5 text-sm text-zinc-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 dark:focus:border-slate-400 dark:focus:ring-slate-900"
                        />
                        <button class="rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-800">Buscar</button>
                        <a href="{{ route('notes.index') }}" class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-4 py-2.5 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                            Todas las notas
                        </a>
                        <a href="{{ route('notes.export.excel', request()->query()) }}" class="inline-flex items-center justify-center rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-800">
                            Excel
                        </a>
                        <a href="{{ route('notes.export.pdf', request()->query()) }}" class="inline-flex items-center justify-center rounded-xl border border-red-300 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-100 hover:text-red-800">
                            PDF
                        </a>
                    </div>
                </form>

                @if($date !== '')
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 dark:border-slate-900/60 dark:bg-slate-950/30 dark:text-slate-200">
                        Mostrando notas para el {{ ucfirst(\Carbon\Carbon::createFromFormat('Y-m-d', $date)->locale('es')->translatedFormat('l d/m/Y')) }}
                    </div>
                @endif

                <div class="grid gap-4 lg:grid-cols-2">
                    @forelse($notes as $note)
                        <article class="rounded-2xl border border-zinc-300 bg-zinc-50 p-5 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900">
                            <div class="mb-4 flex flex-wrap items-start justify-between gap-3 border-b border-zinc-200 pb-4 dark:border-zinc-800">
                                <div>
                                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $note->note_date?->locale('es')->translatedFormat('l d/m/Y H:i') }}</p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('notes.edit', $note) }}" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('Deseas eliminar esta nota?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1.5 text-sm font-medium text-rose-700 transition hover:bg-rose-50 dark:border-rose-900/70 dark:text-rose-300 dark:hover:bg-rose-950/40">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="whitespace-pre-line text-left text-sm leading-6 text-zinc-700 dark:text-zinc-200">{{ $note->content }}</div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-2xl border border-dashed border-zinc-300 bg-white p-8 text-center shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Todavía no hay notas registradas.</p>
                        </div>
                    @endforelse
                </div>

                <div>
                    {{ $notes->links() }}
                </div>
            </section>
        </div>
    </div>
</x-layouts::app>