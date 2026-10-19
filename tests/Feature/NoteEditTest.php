<?php

use App\Models\DailyNote;
use App\Models\User;

it('updates an existing daily note', function (): void {
    $user = User::factory()->create();
    $note = DailyNote::create([
        'note_date' => now()->toDateString(),
        'content' => 'Texto original.',
        'created_by' => $user->id,
    ]);

    $this->actingAs($user)
        ->from(route('notes.edit', $note))
        ->patch(route('notes.update', $note), [
            'content' => 'Texto editado con nuevo teléfono y observación.',
        ])
        ->assertRedirect(route('notes.index'));

    $this->assertDatabaseHas('daily_notes', [
        'id' => $note->id,
        'content' => 'Texto editado con nuevo teléfono y observación.',
    ]);
});