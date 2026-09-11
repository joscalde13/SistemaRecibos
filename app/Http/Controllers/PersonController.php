<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersonController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $people = Person::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('full_name', 'like', "%{$search}%");
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('people.index', compact('people', 'search'));
    }

    public function create(): View
    {
        return view('people.create');
    }

    public function store(StorePersonRequest $request): RedirectResponse
    {
        Person::create($request->validated());

        return redirect()
            ->route('people.index')
            ->with('status', 'Persona registrada correctamente.');
    }

    public function show(Person $person): View
    {
        $receipts = $person->receipts()
            ->withSum('payments', 'amount')
            ->latest('issue_date')
            ->paginate(8, ['*'], 'receipts_page');

        $payments = $person->payments()
            ->with('receipt')
            ->latest('payment_date')
            ->paginate(8, ['*'], 'payments_page');

        $totalAmount = (float) $person->receipts()->sum('total_amount');
        $totalPaid = (float) $person->payments()->sum('amount');

        return view('people.show', [
            'person' => $person,
            'receipts' => $receipts,
            'payments' => $payments,
            'totalAmount' => $totalAmount,
            'totalPaid' => $totalPaid,
            'totalPending' => max($totalAmount - $totalPaid, 0),
        ]);
    }

    public function edit(Person $person): View
    {
        return view('people.edit', compact('person'));
    }

    public function update(UpdatePersonRequest $request, Person $person): RedirectResponse
    {
        $person->update($request->validated());

        return redirect()
            ->route('people.show', $person)
            ->with('status', 'Persona actualizada correctamente.');
    }

    public function destroy(Person $person): RedirectResponse
    {
        if ($person->receipts()->exists()) {
            return back()->withErrors([
                'person' => 'No se puede eliminar una persona con recibos asociados.',
            ]);
        }

        $person->delete();

        return redirect()
            ->route('people.index')
            ->with('status', 'Persona eliminada correctamente.');
    }
}
