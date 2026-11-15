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
        Schema::create('offers', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name");
            $table->text("description");
            $table->string("image");
            $table->foreignUuid("category_id")->nullable()->constrained("categories");
            $table->foreignUuid("product_id")->nullable()->constrained("products");
            $table->foreignUuid("offer_type_id")->nullable()->constrained("offer_types");
            $table->dateTime("start_at")->default(now());
            $table->dateTime("end_at");
            $table->boolean('is_active')->default(false);
            $table->decimal('value', 10, 2)->nullable(); // Discount value (e.g., 10 for 10%)
            $table->decimal('min_order_value', 10, 2)->nullable(); // Minimum order value for cart-level offers
            $table->integer('max_uses')->nullable(); // Maximum number of times the offer can be used
            $table->integer('max_uses_per_user')->nullable(); // Maximum number of times the offer can be used by a single user
            $table->integer('max_uses_per_code')->nullable(); // Maximum number of times a single code can be used
            $table->string('code')->nullable(); // Offer code
            // create offer items table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
