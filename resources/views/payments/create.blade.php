<x-layouts::app :title="__('Registrar abono')">
    <div class="mx-auto w-full max-w-4xl p-4 md:p-6">
        @include('partials.flash')

        <div class="mb-4 rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
            Proximo numero de abono: <strong>{{ $nextPaymentNumber }}</strong>
        </div>

        <h1 class="mb-4 text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Registrar abono</h1>

        <form method="POST" action="{{ route('payments.store') }}" class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            @csrf

            <div class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Recibo</label>
                    <select name="receipt_id" required id="receipt_id" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">
                        <option value="">Selecciona un recibo</option>
                        @foreach($receipts as $receipt)
                            @php
                                $paid = (float) ($receipt->payments_sum_amount ?? 0);
                                $balance = max((float) $receipt->total_amount - $paid, 0);
                            @endphp
                            <option value="{{ $receipt->id }}" data-balance="{{ $balance }}" @selected(old('receipt_id', $selectedReceipt?->id) == $receipt->id)>
                                {{ $receipt->receipt_number }} - {{ $receipt->person?->full_name }} - Saldo Q{{ number_format($balance, 2) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Fecha de pago</label>
                    <input type="date" name="payment_date" required value="{{ old('payment_date', now()->format('Y-m-d')) }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Monto (Q)</label>
                    <input type="number" min="0.01" step="0.01" name="amount" required value="{{ old('amount') }}" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm" />
                </div>
                <div class="md:col-span-2 rounded-lg border border-zinc-200 bg-zinc-50 p-3 text-sm text-zinc-700 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                    Saldo actual del recibo seleccionado: <strong id="receipt_balance">Q0.00</strong>
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-zinc-700 dark:text-zinc-200">Nota</label>
                    <textarea name="notes" rows="3" class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-5 flex gap-3">
                <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Guardar abono</button>
                <a href="{{ route('payments.index') }}" class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        const receiptSelect = document.getElementById('receipt_id');
        const balanceLabel = document.getElementById('receipt_balance');

        function updateBalanceLabel() {
            const option = receiptSelect.options[receiptSelect.selectedIndex];
            const balance = option ? Number(option.dataset.balance || 0) : 0;
            balanceLabel.textContent = `Q${balance.toFixed(2)}`;
        }

        receiptSelect.addEventListener('change', updateBalanceLabel);
        updateBalanceLabel();
    </script>
</x-layouts::app>
