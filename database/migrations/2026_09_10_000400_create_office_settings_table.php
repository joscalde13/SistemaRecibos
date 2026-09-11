<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('office_settings', function (Blueprint $table) {
            $table->id();
            $table->string('office_name')->default('OFICINA JURIDICA ALVARO CALDERON S.');
            $table->string('office_address')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('office_email')->nullable();
            $table->string('notary_name')->default('Alvaro Calderon S.');
            $table->string('notary_license')->nullable();
            $table->text('receipt_footer')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->boolean('show_signature')->default(false);
            $table->boolean('allow_overpayment')->default(false);
            $table->boolean('use_digital_signature_provider')->default(false);
            $table->string('digital_signature_provider')->nullable();
            $table->text('digital_signature_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_settings');
    }
};
