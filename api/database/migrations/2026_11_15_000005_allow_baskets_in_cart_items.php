<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A cart line is either a product or a basket (sold as one line at the
     * basket's own price and stock).
     */
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->uuid('product_id')->nullable()->change();
            $table->foreignUuid('basket_id')->nullable()->after('product_id')->constrained('baskets');
            $table->unique(['cart_id', 'basket_id']);
        });

        DB::statement('ALTER TABLE cart_items ADD CONSTRAINT cart_items_one_kind CHECK (num_nonnulls(product_id, basket_id) = 1)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE cart_items DROP CONSTRAINT IF EXISTS cart_items_one_kind');
        DB::table('cart_items')->whereNull('product_id')->delete();

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['cart_id', 'basket_id']);
            $table->dropConstrainedForeignId('basket_id');
            $table->uuid('product_id')->nullable(false)->change();
        });
    }
};
