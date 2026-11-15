<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Discount codes: an offer is redeemed by entering its code (one per order).
     * Orders record the discount; carts remember the code until checkout.
     */
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('discount_type', 10)->default('percent')->after('offer_type_id'); // percent | fixed
            $table->string('image')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->dateTime('end_at')->nullable()->change();
            $table->unique('code');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->string('discount_code', 50)->nullable()->after('status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignUuid('offer_id')->nullable()->after('shipping_fee')->constrained()->nullOnDelete();
            $table->string('discount_code', 50)->nullable()->after('offer_id');
            $table->decimal('discount', 10, 2)->default(0)->after('discount_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('offer_id');
            $table->dropColumn(['discount_code', 'discount']);
        });
        Schema::table('carts', fn (Blueprint $table) => $table->dropColumn('discount_code'));
        Schema::table('offers', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('discount_type');
        });
    }
};
