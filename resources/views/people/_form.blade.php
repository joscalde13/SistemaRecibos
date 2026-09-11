@csrf

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Nombre completo</label>
        <input name="full_name" value="{{ old('full_name', $person->full_name ?? '') }}" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">DPI o identificacion</label>
        <input name="identifier" value="{{ old('identifier', $person->identifier ?? '') }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Telefono</label>
        <input name="phone" value="{{ old('phone', $person->phone ?? '') }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Correo electronico</label>
        <input name="email" type="email" value="{{ old('email', $person->email ?? '') }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Direccion</label>
        <input name="address" value="{{ old('address', $person->address ?? '') }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Fecha de registro</label>
        <input name="registered_at" type="date" value="{{ old('registered_at', isset($person) && $person->registered_at ? $person->registered_at->format('Y-m-d') : now()->format('Y-m-d')) }}" required class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Notas</label>
        <textarea name="notes" rows="3" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">{{ old('notes', $person->notes ?? '') }}</textarea>
    </div>
</div>
