<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $minMonth = now()->startOfYear();
        $maxMonth = now()->addYears(5)->endOfYear()->startOfMonth();

        try {
            $month = Carbon::createFromFormat('Y-m-d', $request->query('month', now()->format('Y-m')).'-01')->startOfMonth();
        } catch (\Throwable) {
            $month = now()->startOfMonth();
        }
        $month = $month->lt($minMonth) ? $minMonth->copy() : ($month->gt($maxMonth) ? $maxMonth->copy() : $month);

        $events = CalendarEvent::query()
            ->whereBetween('event_date', [$month->toDateString(), $month->copy()->endOfMonth()->toDateString()])
            ->orderBy('event_time')
            ->get()
            ->groupBy(fn (CalendarEvent $e) => $e->event_date->toDateString());

        return view('calendar.index', [
            'month' => $month,
            'events' => $events,
            'prev' => $month->gt($minMonth) ? $month->copy()->subMonth()->format('Y-m') : null,
            'next' => $month->lt($maxMonth) ? $month->copy()->addMonth()->format('Y-m') : null,
            'years' => range($minMonth->year, $maxMonth->year),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['created_by'] = $request->user()->id;
        $event = CalendarEvent::create($data);

        return redirect()->route('calendar.index', ['month' => $event->event_date->format('Y-m')])
            ->with('status', 'Evento guardado.');
    }

    public function update(Request $request, CalendarEvent $event): RedirectResponse
    {
        $event->update($this->validated($request));

        return redirect()->route('calendar.index', ['month' => $event->event_date->format('Y-m')])
            ->with('status', 'Evento actualizado.');
    }

    public function destroy(CalendarEvent $event): RedirectResponse
    {
        $month = $event->event_date->format('Y-m');
        $event->delete();

        return redirect()->route('calendar.index', ['month' => $month])->with('status', 'Evento eliminado.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:5000'],
            'event_date' => ['required', 'date'],
            'event_time' => ['nullable', 'date_format:H:i'],
            'remind_days_before' => ['required', 'integer', 'min:0', 'max:365'],
        ]);

        return $data;
    }
}
