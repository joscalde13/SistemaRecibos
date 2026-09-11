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
        Schema::table('receipts', function (Blueprint $table) {
            if (! Schema::hasColumn('receipts', 'saldo_amount')) {
                $table->decimal('saldo_amount', 12, 2)->default(0)->after('abono_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            if (Schema::hasColumn('receipts', 'saldo_amount')) {
                $table->dropColumn('saldo_amount');
            }
        });
    }
};
