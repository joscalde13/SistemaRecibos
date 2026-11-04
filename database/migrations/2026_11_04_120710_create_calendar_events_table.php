<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('note')->nullable();
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->unsignedSmallInteger('remind_days_before')->default(3);
            $table->timestamps();

            $table->index('event_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
