<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEscrituraRequest;
use App\Http\Requests\UpdateEscrituraRequest;
use App\Models\Escritura;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class EscrituraController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $status = trim((string) $request->string('status'));

        $escrituras = Escritura::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner->where('client_name', 'like', "%{$search}%")
                        ->orWhere('escritura_number', 'like', "%{$search}%");
                });
            })
            ->when($status !== '', function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->orderByRaw("case when status = ? then 0 when status = ? then 1 when status = ? then 2 else 3 end", [
                Escritura::STATUS_PENDING_SEND_REGISTRY,
                Escritura::STATUS_PENDING_REGISTRY,
                Escritura::STATUS_PENDING_DELIVERY,
            ])
            ->latest('entry_date')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $summary = [
            'pending_send_registry' => Escritura::query()->where('status', Escritura::STATUS_PENDING_SEND_REGISTRY)->count(),
            'pending_registry' => Escritura::query()->where('status', Escritura::STATUS_PENDING_REGISTRY)->count(),
            'pending_delivery' => Escritura::query()->where('status', Escritura::STATUS_PENDING_DELIVERY)->count(),
            'delivered' => Escritura::query()->where('status', Escritura::STATUS_DELIVERED)->count(),
        ];

        return view('escrituras.index', [
            'escrituras' => $escrituras,
            'search' => $search,
            'status' => $status,
            'statuses' => Escritura::statuses(),
            'summary' => $summary,
        ]);
    }

    public function create(): View
    {
        return view('escrituras.create', [
            'statuses' => Escritura::statuses(),
            'defaultEntryDate' => now()->toDateString(),
        ]);
    }

    public function store(StoreEscrituraRequest $request): RedirectResponse
    {
        $escritura = Escritura::create($this->normalizePayload($request->validated()));

        return redirect()
            ->route('escrituras.index')
            ->with('status', 'Escritura registrada correctamente.');
    }

    public function show(Escritura $escritura): View
    {
        return view('escrituras.show', [
            'escritura' => $escritura,
            'statuses' => Escritura::statuses(),
        ]);
    }

    public function edit(Escritura $escritura): View
    {
        return view('escrituras.edit', [
            'escritura' => $escritura,
            'statuses' => Escritura::statuses(),
        ]);
    }

    public function update(UpdateEscrituraRequest $request, Escritura $escritura): RedirectResponse
    {
        $escritura->update($this->normalizePayload($request->validated()));

        return redirect()
            ->route('escrituras.index')
            ->with('status', 'Escritura actualizada correctamente.');
    }

    public function destroy(Escritura $escritura): RedirectResponse
    {
        $escritura->delete();

        return redirect()
            ->route('escrituras.index')
            ->with('status', 'Escritura eliminada correctamente.');
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function normalizePayload(array $data): array
    {
        $entryDate = $this->normalizeDate($data['entry_date'] ?? null) ?? now()->toDateString();
        $registryReceivedDate = $this->normalizeDate($data['registry_received_date'] ?? null);
        $deliveryDate = $this->normalizeDate($data['delivery_date'] ?? null);
        $status = $data['status'] ?? Escritura::STATUS_PENDING_SEND_REGISTRY;

        if ($deliveryDate || $status === Escritura::STATUS_DELIVERED) {
            $status = Escritura::STATUS_DELIVERED;
        } elseif ($registryReceivedDate || $status === Escritura::STATUS_PENDING_DELIVERY) {
            $status = Escritura::STATUS_PENDING_DELIVERY;
        } elseif ($status === Escritura::STATUS_PENDING_REGISTRY) {
            $status = Escritura::STATUS_PENDING_REGISTRY;
        } else {
            $status = Escritura::STATUS_PENDING_SEND_REGISTRY;
        }

        if ($status === Escritura::STATUS_DELIVERED && ! $deliveryDate) {
            $deliveryDate = now()->toDateString();
        }

        if ($status !== Escritura::STATUS_DELIVERED) {
            $deliveryDate = $status === Escritura::STATUS_PENDING_DELIVERY ? $deliveryDate : null;
        }

        return [
            ...$data,
            'entry_date' => $entryDate,
            'registry_received_date' => $registryReceivedDate,
            'delivery_date' => $deliveryDate,
            'status' => $status,
        ];
    }

    private function normalizeDate(mixed $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        return Carbon::parse($value)->toDateString();
    }
}