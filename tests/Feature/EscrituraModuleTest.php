<?php

use App\Models\Escritura;
use App\Models\User;

test('authenticated users can view the escrituras index', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('escrituras.index'));

    $response
        ->assertOk()
        ->assertSee('Escrituras');
});

test('a escritura is stored as pending send registry by default when no follow-up dates are provided', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('escrituras.store'), [
        'client_name' => 'Cliente Prueba',
        'escritura_number' => 'ESC-001',
        'entry_date' => '2026-09-30',
        'registry_received_date' => null,
        'delivery_date' => null,
        'status' => Escritura::STATUS_PENDING_SEND_REGISTRY,
        'notes' => 'Primera escritura',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('escrituras', [
        'client_name' => 'Cliente Prueba',
        'escritura_number' => 'ESC-001',
        'status' => Escritura::STATUS_PENDING_SEND_REGISTRY,
    ]);
});

test('a escritura can be stored as pending registry when it was already sent but has not returned', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('escrituras.store'), [
        'client_name' => 'Cliente Enviado',
        'escritura_number' => 'ESC-001A',
        'entry_date' => '2026-09-30',
        'registry_received_date' => null,
        'delivery_date' => null,
        'status' => Escritura::STATUS_PENDING_REGISTRY,
        'notes' => 'Ya fue enviada al registro',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('escrituras', [
        'client_name' => 'Cliente Enviado',
        'escritura_number' => 'ESC-001A',
        'status' => Escritura::STATUS_PENDING_REGISTRY,
    ]);
});

test('a escritura keeps pending delivery when that state is selected manually', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('escrituras.store'), [
        'client_name' => 'Cliente Pendiente Entrega',
        'escritura_number' => 'ESC-001B',
        'entry_date' => '2026-09-30',
        'registry_received_date' => null,
        'delivery_date' => null,
        'status' => Escritura::STATUS_PENDING_DELIVERY,
        'notes' => 'Pendiente de entregar al cliente',
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('escrituras', [
        'client_name' => 'Cliente Pendiente Entrega',
        'escritura_number' => 'ESC-001B',
        'status' => Escritura::STATUS_PENDING_DELIVERY,
    ]);
});

test('a escritura is marked as pending delivery when it already came from the registry', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('escrituras.store'), [
        'client_name' => 'Cliente Registro',
        'escritura_number' => 'ESC-002',
        'entry_date' => '2026-09-20',
        'registry_received_date' => '2026-09-25',
        'delivery_date' => null,
        'status' => Escritura::STATUS_PENDING_REGISTRY,
        'notes' => null,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('escrituras', [
        'escritura_number' => 'ESC-002',
        'status' => Escritura::STATUS_PENDING_DELIVERY,
    ]);
});

test('a escritura is marked as delivered when delivery date is provided', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('escrituras.store'), [
        'client_name' => 'Cliente Entrega',
        'escritura_number' => 'ESC-003',
        'entry_date' => '2026-09-15',
        'registry_received_date' => '2026-09-18',
        'delivery_date' => '2026-09-22',
        'status' => Escritura::STATUS_PENDING_REGISTRY,
        'notes' => 'Entregada al cliente',
    ]);

    $response->assertRedirect();

    $escritura = Escritura::query()->where('escritura_number', 'ESC-003')->firstOrFail();

    $this->assertDatabaseHas('escrituras', [
        'escritura_number' => 'ESC-003',
        'status' => Escritura::STATUS_DELIVERED,
    ]);

    expect($escritura->delivery_date?->format('Y-m-d'))->toBe('2026-09-22');
});