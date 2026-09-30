<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700">Nombre del cliente</label>
        <input name="client_name" value="{{ old('client_name', $escritura->client_name ?? '') }}" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700">Numero de escritura</label>
        <input name="escritura_number" value="{{ old('escritura_number', $escritura->escritura_number ?? '') }}" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700">Fecha de creación de escritura</label>
        <input type="date" name="entry_date" value="{{ old('entry_date', isset($escritura) ? $escritura->entry_date?->format('Y-m-d') : $defaultEntryDate) }}" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
        <p class="mt-1 text-xs text-zinc-500">Se llena por defecto con la fecha actual de Guatemala.</p>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700">Estado</label>
        <select name="status" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-700">
            @foreach($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $escritura->status ?? \App\Models\Escritura::STATUS_PENDING_SEND_REGISTRY) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700">Fecha recibida del registro</label>
        <input type="date" name="registry_received_date" value="{{ old('registry_received_date', isset($escritura) && $escritura->registry_received_date ? $escritura->registry_received_date->format('Y-m-d') : '') }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700">Fecha de entrega</label>
        <input type="date" name="delivery_date" value="{{ old('delivery_date', isset($escritura) && $escritura->delivery_date ? $escritura->delivery_date->format('Y-m-d') : '') }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-zinc-700">Observaciones</label>
        <textarea name="notes" rows="4" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">{{ old('notes', $escritura->notes ?? '') }}</textarea>
    </div>
</div>