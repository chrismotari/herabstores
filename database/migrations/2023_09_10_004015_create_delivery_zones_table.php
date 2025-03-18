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
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name")->unique();
            $table->string("slug")->unique()->nullable();
            $table->text("description")->nullable();
            $table->decimal("delivery_fees", 10, 2)->default(0);
            $table->foreignId("estate_id")->nullable()->constrained();
            $table->foreignId("county_id")->nullable()->constrained();
            $table->foreignUlid("store_id")->constrained();
            $table->boolean("is_active")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};
