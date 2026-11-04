<x-layouts::app :title="__('Calendario')">
    @php
        $daysInMonth = $month->daysInMonth;
        $offset = $month->dayOfWeek;
        $payload = $events->map(fn ($list) => $list->map(fn ($e) => [
            'id' => $e->id,
            'title' => $e->title,
            'note' => $e->note,
            'time' => $e->event_time ? substr($e->event_time, 0, 5) : '',
            'remind' => $e->remind_days_before,
            'date' => $e->event_date->toDateString(),
        ])->values());
    @endphp

    <div class="mx-auto w-full max-w-7xl px-1 py-3 sm:p-4 md:p-6"
         x-data="{
            open: false, id: null, date: '', title: '', note: '', time: '', remind: 3, list: [],
            data: @js($payload),
            pick(d) { this.date = d; this.list = this.data[d] || []; this.reset(); this.open = true; },
            reset() { this.id = null; this.title = ''; this.note = ''; this.time = ''; this.remind = 3; },
            edit(e) { this.id = e.id; this.title = e.title; this.note = e.note || ''; this.time = e.time; this.remind = e.remind; },
            action() { return this.id ? '{{ url('calendar') }}/' + this.id : '{{ route('calendar.store') }}'; }
         }">
        <div class="mb-3 flex items-center justify-between gap-2 px-2 sm:mb-4 sm:px-0 sm:gap-3">
            @if ($prev)
                <a href="{{ route('calendar.index', ['month' => $prev]) }}" class="rounded-lg border border-zinc-300 px-3 py-2 text-base sm:px-4 sm:text-lg dark:border-zinc-700 dark:text-zinc-200">&larr;</a>
            @else
                <span class="rounded-lg border border-zinc-200 px-3 py-2 text-base text-zinc-300 sm:px-4 sm:text-lg dark:border-zinc-800">&larr;</span>
            @endif
            <form method="GET" action="{{ route('calendar.index') }}" class="flex items-center gap-2" x-data
                  x-on:change="$refs.m.value = $refs.y.value + '-' + $refs.mm.value; $el.submit()">
                <input type="hidden" name="month" x-ref="m" value="{{ $month->format('Y-m') }}">
                <select x-ref="mm" class="rounded-lg border border-zinc-300 px-2 py-2 text-base font-semibold sm:px-3 sm:text-lg capitalize dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" @selected($m === $month->month)>{{ \Carbon\Carbon::create(2000, $m, 1)->locale('es')->translatedFormat('F') }}</option>
                    @endfor
                </select>
                <select x-ref="y" class="rounded-lg border border-zinc-300 px-2 py-2 text-base font-semibold sm:px-3 sm:text-lg dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                    @foreach ($years as $y)
                        <option value="{{ $y }}" @selected($y === $month->year)>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
            @if ($next)
                <a href="{{ route('calendar.index', ['month' => $next]) }}" class="rounded-lg border border-zinc-300 px-3 py-2 text-base sm:px-4 sm:text-lg dark:border-zinc-700 dark:text-zinc-200">&rarr;</a>
            @else
                <span class="rounded-lg border border-zinc-200 px-3 py-2 text-base text-zinc-300 sm:px-4 sm:text-lg dark:border-zinc-800">&rarr;</span>
            @endif
        </div>

        @if (session('status'))
            <div class="mx-2 mb-4 sm:mx-0 rounded-lg bg-emerald-100 px-4 py-2 text-sm text-emerald-800">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 px-4 py-2 text-sm text-red-800">{{ $errors->first() }}</div>
        @endif

        <div class="grid grid-cols-7 gap-0.5 text-center text-sm font-bold sm:gap-2 uppercase text-zinc-500">
            @foreach (['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $d)
                <div class="py-2"><span class="sm:hidden">{{ mb_substr($d, 0, 1) }}</span><span class="hidden sm:inline">{{ $d }}</span></div>
            @endforeach
        </div>
        <div class="grid grid-cols-7 gap-0.5 sm:gap-2">
            @for ($i = 0; $i < $offset; $i++)
                <div></div>
            @endfor
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = $month->copy()->day($day)->toDateString();
                    $count = $events->get($date)?->count() ?? 0;
                @endphp
                <button type="button" @click="pick('{{ $date }}')"
                    class="flex h-[4.5rem] flex-col items-center justify-between rounded-md border px-0.5 py-1.5 text-center text-lg sm:h-24 sm:rounded-lg sm:items-start sm:p-3 sm:text-left sm:text-base md:h-32 lg:h-36 text-zinc-900 transition hover:bg-amber-50 dark:text-zinc-100 dark:hover:bg-zinc-800 {{ $date === today()->toDateString() ? 'border-amber-400 bg-amber-50 dark:bg-zinc-800' : 'border-zinc-200 dark:border-zinc-700' }}">
                    <span class="font-semibold leading-none {{ $count ? '' : 'my-auto sm:my-0' }}">{{ $day }}</span>
                    @if ($count)
                        <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-400 px-1 text-xs font-bold leading-none text-zinc-900 sm:hidden">{{ $count }}</span>
                        <span class="sm:mt-auto hidden rounded-full bg-amber-400 px-2 py-0.5 text-xs font-semibold text-zinc-900 sm:inline">{{ $count }} {{ $count === 1 ? 'evento' : 'eventos' }}</span>
                    @endif
                </button>
            @endfor
        </div>

        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-3 sm:p-4" @keydown.escape.window="open = false">
            <div class="max-h-[85vh] w-full max-w-md overflow-y-auto rounded-2xl bg-white p-4 shadow-2xl sm:p-5 shadow-xl dark:bg-zinc-900" @click.outside="open = false">
                <h2 class="mb-3 text-lg font-semibold text-zinc-900 dark:text-zinc-100">Eventos del <span x-text="date.split('-').reverse().join('/')"></span></h2>

                <template x-for="e in list" :key="e.id">
                    <div class="mb-2 flex items-start justify-between gap-2 rounded-lg border border-zinc-200 p-2 text-sm dark:border-zinc-700 dark:text-zinc-200">
                        <div>
                            <p class="font-semibold" x-text="e.title"></p>
                            <p class="text-xs text-zinc-500" x-text="(e.time ? e.time + ' · ' : '') + 'Aviso ' + e.remind + ' día(s) antes'"></p>
                            <p class="text-xs" x-text="e.note"></p>
                        </div>
                        <div class="flex shrink-0 gap-2 text-xs">
                            <button type="button" class="text-blue-600 underline" @click="edit(e)">Editar</button>
                            <form method="POST" :action="'{{ url('calendar') }}/' + e.id" onsubmit="return confirm('¿Eliminar evento?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 underline">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </template>

                <form method="POST" :action="action()" class="mt-3 space-y-3">
                    @csrf
                    <template x-if="id"><input type="hidden" name="_method" value="PATCH"></template>
                    <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300" x-text="id ? 'Editar evento' : 'Nuevo evento'"></p>
                    <input type="text" name="title" x-model="title" required maxlength="255" placeholder="Título (ej. Audiencia)" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                    <textarea name="note" x-model="note" rows="3" placeholder="Nota" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"></textarea>
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                        <label class="text-xs text-zinc-500">Fecha
                            <input type="date" name="event_date" x-model="date" required class="w-full rounded-lg border border-zinc-300 px-2 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                        </label>
                        <label class="text-xs text-zinc-500">Hora
                            <input type="time" name="event_time" x-model="time" class="w-full rounded-lg border border-zinc-300 px-2 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                        </label>
                        <label class="text-xs text-zinc-500">Avisar (días antes)
                            <input type="number" name="remind_days_before" x-model="remind" min="0" max="365" required class="w-full rounded-lg border border-zinc-300 px-2 py-2 text-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                        </label>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="id ? reset() : open = false" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm dark:border-zinc-700 dark:text-zinc-200" x-text="id ? 'Cancelar edición' : 'Cerrar'"></button>
                        <button class="rounded-lg bg-amber-400 px-4 py-2 text-sm font-semibold text-zinc-900 hover:bg-amber-300">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
