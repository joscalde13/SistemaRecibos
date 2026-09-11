<x-layouts::app :title="__('Configuracion')">
    <div class="mx-auto w-full max-w-5xl p-4 md:p-6">
        @include('partials.flash')

        <h1 class="mb-4 text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Configuracion de la oficina</h1>

        <form method="POST" action="{{ route('office-settings.update') }}" enctype="multipart/form-data" class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @csrf
            @method('PUT')

            <div class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium">Nombre de la oficina</label>
                    <input name="office_name" required value="{{ old('office_name', $officeSetting->office_name) }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Direccion</label>
                    <input name="office_address" value="{{ old('office_address', $officeSetting->office_address) }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Telefono</label>
                    <input name="office_phone" value="{{ old('office_phone', $officeSetting->office_phone) }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Correo</label>
                    <input type="email" name="office_email" value="{{ old('office_email', $officeSetting->office_email) }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Nombre del notario</label>
                    <input name="notary_name" required value="{{ old('notary_name', $officeSetting->notary_name) }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Numero de colegiado</label>
                    <input name="notary_license" value="{{ old('notary_license', $officeSetting->notary_license) }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Logo</label>
                    <input type="file" name="logo" accept="image/*" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                    @if($officeSetting->logo_path)
                        <img src="{{ asset('storage/'.$officeSetting->logo_path) }}" alt="Logo" class="mt-2 h-12 rounded" />
                    @endif
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Firma del notario</label>
                    <input type="file" name="signature" accept="image/*" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                    @if($officeSetting->signature_path)
                        <img src="{{ asset('storage/'.$officeSetting->signature_path) }}" alt="Firma" class="mt-2 h-12 rounded" />
                    @endif
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium">Informacion adicional para recibo</label>
                    <textarea name="receipt_footer" rows="3" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">{{ old('receipt_footer', $officeSetting->receipt_footer) }}</textarea>
                </div>
            </div>

            <div class="mt-5 grid gap-3 md:grid-cols-2">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="show_signature" value="1" @checked(old('show_signature', $officeSetting->show_signature))>
                    Mostrar firma cargada en recibos/PDF
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="allow_overpayment" value="1" @checked(old('allow_overpayment', $officeSetting->allow_overpayment))>
                    Permitir pagos mayores al saldo pendiente
                </label>
                <label class="flex items-center gap-2 text-sm md:col-span-2">
                    <input type="checkbox" name="use_digital_signature_provider" value="1" @checked(old('use_digital_signature_provider', $officeSetting->use_digital_signature_provider))>
                    Usar proveedor de firma electronica/digital (estructura preparatoria)
                </label>
                <div>
                    <label class="mb-1 block text-sm font-medium">Proveedor de firma digital (opcional)</label>
                    <input name="digital_signature_provider" value="{{ old('digital_signature_provider', $officeSetting->digital_signature_provider) }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Notas de integracion</label>
                    <input name="digital_signature_notes" value="{{ old('digital_signature_notes', $officeSetting->digital_signature_notes) }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                </div>
            </div>

            <div class="mt-5">
                <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Guardar configuracion</button>
            </div>
        </form>
    </div>
</x-layouts::app>
