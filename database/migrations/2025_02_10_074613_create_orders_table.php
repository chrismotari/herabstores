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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid('order_number')->unique();
            $table->foreignUuid("user_id")->constrained("users");
            $table->decimal("total", 10, 2)->default(0);//calculated value
            $table->json('order_items')->nullable();//add all cart items in json format once the order is placed
            $table->string("status")->nullable();
            $table->string("payment_status")->nullable();
            $table->string("payment_method")->nullable();
            $table->string("shipping_method")->nullable();
            $table->string("shipping_address")->nullable();
            $table->string("billing_address")->nullable();
            $table->timestamp("shipped_at")->nullable();
            $table->timestamp("delivered_at")->nullable();
            $table->timestamp("canceled_at")->nullable();
            $table->timestamp("refunded_at")->nullable();
            $table->timestamp("failed_at")->nullable();
            $table->timestamp("paid_at")->nullable();
            $table->timestamp("completed_at")->nullable();
            $table->timestamp("expired_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
