<?php

use App\Models\DailyNote;
use App\Models\User;

it('shows the notes module to authenticated users', function (): void {
    $this->actingAs(User::factory()->create())
        ->get(route('notes.index'))
        ->assertOk()
        ->assertSee('Notas del día');
});

it('stores a daily note with free text content', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('notes.index'))
        ->post(route('notes.store'), [
            'content' => 'Juan Pérez, 5555-5555, pidió seguimiento de su trámite.',
        ])
        ->assertRedirect(route('notes.index'));

    $note = DailyNote::query()->latest()->first();

    $this->assertNotNull($note);
    $this->assertSame('Juan Pérez, 5555-5555, pidió seguimiento de su trámite.', $note->content);
    $this->assertSame($user->id, $note->created_by);
    $this->assertNotSame('00:00:00', $note->note_date->format('H:i:s'));
});

it('exports notes as excel', function (): void {
    $user = User::factory()->create();
    DailyNote::create([
        'note_date' => '2026-10-20',
        'content' => 'Juan Pérez, 5555-5555, revisión pendiente.',
        'created_by' => $user->id,
    ]);

    $response = $this->actingAs($user)
        ->get(route('notes.export.excel', ['date' => '2026-10-20']));

    $response->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

    $this->assertStringContainsString('Fecha', $response->streamedContent());
    $this->assertStringContainsString('Juan Pérez', $response->streamedContent());
});

it('exports notes as pdf', function (): void {
    $user = User::factory()->create();
    DailyNote::create([
        'note_date' => '2026-10-20',
        'content' => 'Cliente solicita información urgente.',
        'created_by' => $user->id,
    ]);

    $this->actingAs($user)
        ->get(route('notes.export.pdf', ['date' => '2026-10-20']))
        ->assertOk()
        ->assertDownload('reporte-de-notas.pdf');
});

it('deletes a daily note', function (): void {
    $user = User::factory()->create();
    $note = DailyNote::create([
        'note_date' => now()->toDateString(),
        'content' => 'Nota temporal para borrar.',
        'created_by' => $user->id,
    ]);

    $this->actingAs($user)
        ->delete(route('notes.destroy', $note))
        ->assertRedirect(route('notes.index'));

    $this->assertDatabaseMissing('daily_notes', [
        'id' => $note->id,
    ]);
});