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
        Schema::create('stores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('slug')->unique()->nullable();
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->foreignUuid('manager_id')->nullable()->constrained('users');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->string('alt_phone')->unique()->nullable();
            $table->text('address'); 
            $table->foreignUuid('delivery_zone_id')->nullable()->constrained()->nullOnDelete(); 
            $table->string('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
