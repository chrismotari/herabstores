<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sign-up no longer asks for an address; it is collected at checkout.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable()->change();
            $table->string('city')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
        });
    }
};
