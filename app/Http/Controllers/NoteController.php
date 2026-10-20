<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDailyNoteRequest;
use App\Models\DailyNote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $date = trim((string) $request->string('date'));

        $notes = $this->filteredQuery($request)
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

    public function exportExcel(Request $request)
    {
        $notes = $this->filteredQuery($request)->get();
        $filename = 'notas-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($notes): void {
            $handle = fopen('php://output', 'wb');
            fputcsv($handle, ['Fecha', 'Nota']);

            foreach ($notes as $note) {
                $date = $note->note_date
                    ? $note->note_date->locale('es')->translatedFormat('l d/m/Y')
                    : '';

                fputcsv($handle, [
                    $date,
                    $note->content ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $notes = $this->filteredQuery($request)->get();

        $pdf = Pdf::loadView('notes.export-pdf', [
            'notes' => $notes,
        ])->setPaper('letter', 'landscape');

        return $pdf->download('reporte-de-notas.pdf');
    }

    private function filteredQuery(Request $request)
    {
        $search = trim((string) $request->string('search'));
        $date = trim((string) $request->string('date'));

        return DailyNote::query()
            ->with('creator')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('content', 'like', "%{$search}%");
            })
            ->when($date !== '', function ($query) use ($date): void {
                $query->whereDate('note_date', $date);
            })
            ->latest('note_date')
            ->latest('id');
    }
}