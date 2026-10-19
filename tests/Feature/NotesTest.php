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

    $this->assertDatabaseHas('daily_notes', [
        'content' => 'Juan Pérez, 5555-5555, pidió seguimiento de su trámite.',
        'created_by' => $user->id,
    ]);
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