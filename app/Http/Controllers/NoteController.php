<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyNoteRequest;
use App\Models\DailyNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $date = trim((string) $request->string('date'));

        $notes = DailyNote::query()
            ->with('creator')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('content', 'like', "%{$search}%");
            })
            ->when($date !== '', function ($query) use ($date): void {
                $query->whereDate('note_date', $date);
            })
            ->latest('note_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('notes.index', compact('notes', 'search', 'date'));
    }

    public function store(StoreDailyNoteRequest $request): RedirectResponse
    {
        DailyNote::create([
            'content' => $request->validated('content'),
            'note_date' => now()->toDateString(),
            'created_by' => $request->user()?->id,
        ]);

        return redirect()
            ->route('notes.index')
            ->with('status', 'Nota guardada correctamente.');
    }

    public function edit(DailyNote $note): View
    {
        return view('notes.edit', compact('note'));
    }

    public function update(StoreDailyNoteRequest $request, DailyNote $note): RedirectResponse
    {
        $note->update([
            'content' => $request->validated('content'),
        ]);

        return redirect()
            ->route('notes.index')
            ->with('status', 'Nota actualizada correctamente.');
    }

    public function destroy(DailyNote $note): RedirectResponse
    {
        $note->delete();

        return redirect()
            ->route('notes.index')
            ->with('status', 'Nota eliminada correctamente.');
    }
}