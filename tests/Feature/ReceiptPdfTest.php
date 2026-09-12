<?php

use App\Models\Person;
use App\Models\Receipt;
use App\Models\User;

test('receipt pdf uses the total amount in words, not the payment amount', function () {
    $user = User::factory()->create();

    $person = Person::query()->create([
        'full_name' => 'Juan Pérez',
        'registered_at' => now()->toDateString(),
    ]);

    $receipt = Receipt::create([
        'person_id' => $person->id,
        'created_by' => $user->id,
        'receipt_number' => 'REC-000001',
        'issue_date' => now()->toDateString(),
        'concept' => 'Pago de mantenimiento',
        'total_amount' => 1500.00,
        'abono_amount' => 300.00,
        'saldo_amount' => 1200.00,
        'status' => Receipt::STATUS_PARTIAL,
    ]);

    $response = $this->actingAs($user)->get(route('receipts.pdf', $receipt));

    $response->assertOk();
    $responseContent = $response->getContent();

    expect($responseContent)->toContain('MIL QUINIENTOS');
    expect($responseContent)->not->toContain('TRESCIENTOS');
    expect($responseContent)->toContain('Q1,200.00');
});

test('manual receipt values are stored and reflected in the index and pdf', function () {
    $user = User::factory()->create();
    $person = Person::query()->create([
        'full_name' => 'Ana López',
        'registered_at' => now()->toDateString(),
    ]);

    $receipt = Receipt::create([
        'person_id' => $person->id,
        'created_by' => $user->id,
        'receipt_number' => 'REC-000002',
        'issue_date' => now()->toDateString(),
        'concept' => 'Cuota mensual',
        'total_amount' => 100.00,
        'abono_amount' => 50.00,
        'saldo_amount' => 25.00,
        'status' => Receipt::STATUS_PARTIAL,
    ]);

    expect((float) $receipt->saldo_amount)->toBe(25.00)
        ->and((float) $receipt->abono_amount)->toBe(50.00)
        ->and((float) $receipt->total_amount)->toBe(100.00);

    $this->actingAs($user)
        ->get(route('receipts.index'))
        ->assertOk()
        ->assertSee('Q25.00');

    $this->actingAs($user)
        ->get(route('receipts.pdf', $receipt))
        ->assertOk()
        ->assertSee('Q25.00');
});

test('receipt export csv includes the concept column', function () {
    $user = User::factory()->create();
    $person = Person::query()->create([
        'full_name' => 'Luis Ramírez',
        'registered_at' => now()->toDateString(),
    ]);

    Receipt::create([
        'person_id' => $person->id,
        'created_by' => $user->id,
        'receipt_number' => 'REC-000003',
        'issue_date' => now()->toDateString(),
        'concept' => 'Honorarios legales',
        'total_amount' => 250.00,
        'abono_amount' => 0.00,
        'saldo_amount' => 250.00,
        'status' => Receipt::STATUS_PENDING,
    ]);

    $response = $this->actingAs($user)->get(route('receipts.export.excel'));

    $response->assertOk();

    ob_start();
    $response->sendContent();
    $content = ob_get_clean();

    expect($content)
        ->toContain('Concepto')
        ->toContain('Honorarios legales');
});

test('receipt export pdf includes the concept column', function () {
    $user = User::factory()->create();
    $person = Person::query()->create([
        'full_name' => 'Sofía Gómez',
        'registered_at' => now()->toDateString(),
    ]);

    $receipt = Receipt::create([
        'person_id' => $person->id,
        'created_by' => $user->id,
        'receipt_number' => 'REC-000004',
        'issue_date' => now()->toDateString(),
        'concept' => 'Pago de cuota',
        'total_amount' => 500.00,
        'abono_amount' => 100.00,
        'saldo_amount' => 400.00,
        'status' => Receipt::STATUS_PARTIAL,
    ]);

    $html = view('receipts.export-pdf', ['receipts' => [$receipt]])->render();

    expect($html)
        ->toContain('<th>Concepto</th>')
        ->toContain('Pago de cuota');
});
