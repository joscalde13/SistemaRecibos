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
