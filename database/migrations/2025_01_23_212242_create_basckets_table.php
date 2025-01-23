<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('basckets', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name");
            $table->text("description");
            $table->decimal("price", 10, 2);
            $table->integer("stock");
            $table->string("image")->nullable();
            $table->boolean("active")->default(true);
            $table->dateTime("effective_from")->default(now());
            $table->dateTime("effective_to");
            $table->json("items");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basckets');
    }
};
