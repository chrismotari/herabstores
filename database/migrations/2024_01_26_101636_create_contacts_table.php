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
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('consented')->default(true);
            $table->text('message')->nullable();
            $table->string('source')->nullable();
            $table->string('referree_code')->nullable();
            $table->string('country_id')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
