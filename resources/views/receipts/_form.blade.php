@csrf

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Recibimos de (nombre)</label>
        <input id="person_name" name="person_name" list="people_list" required value="{{ old('person_name', $receipt->person->full_name ?? '') }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
        <datalist id="people_list">
            @foreach($people as $personOption)
                <option value="{{ $personOption->full_name }}"></option>
            @endforeach
        </datalist>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">DPI / identificacion (opcional)</label>
        <input id="person_identifier" name="person_identifier" value="{{ old('person_identifier', $receipt->person->identifier ?? '') }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Fecha</label>
        <input name="issue_date" type="date" value="{{ old('issue_date', isset($receipt) && $receipt->issue_date ? $receipt->issue_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Monto total (Q)</label>
        <input id="total_amount" name="total_amount" type="number" step="0.01" min="0.01" value="{{ old('total_amount', $receipt->total_amount ?? '') }}" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Abono (Q)</label>
        <input id="abono_amount" name="abono_amount" type="number" step="0.01" min="0" value="{{ old('abono_amount', $receipt->abono_amount ?? 0) }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Saldo (Q)</label>
        <input id="saldo_amount" name="saldo_amount" type="number" step="0.01" min="0" value="{{ old('saldo_amount', $receipt->saldo_amount ?? 0) }}" placeholder="Ingrese saldo manual" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Concepto</label>
        <input name="concept" value="{{ old('concept', $receipt->concept ?? '') }}" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Notas</label>
        <textarea name="notes" rows="3" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">{{ old('notes', $receipt->notes ?? '') }}</textarea>
    </div>
</div>

