<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Delivery is priced per zone (decision 2026-11-15); estate fees are unused.
     */
    public function up(): void
    {
        Schema::table('estates', function (Blueprint $table) {
            $table->dropColumn('delivery_fees');
        });
    }

    public function down(): void
    {
        Schema::table('estates', function (Blueprint $table) {
            $table->decimal('delivery_fees', 10, 2)->default(0);
        });
    }
};
