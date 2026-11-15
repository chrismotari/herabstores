<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reordering images in the admin updates sort_order row by row; a unique
     * (product_id, sort_order) index makes swaps fail midway.
     */
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'sort_order']);
            $table->index(['product_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'sort_order']);
            $table->unique(['product_id', 'sort_order']);
        });
    }
};
