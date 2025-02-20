<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\TextUI\XmlConfiguration\Logging\TeamCity;

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
            $table->json('order_items');//add all cart items in json format once the order is placed
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
            $table->string("fname")->nullable();
            $table->string("lname")->nullable();
            $table->string("company")->nullable();
            $table->string("phone")->nullable();
            $table->string("alt_phone")->nullable();
            $table->string("email")->nullable();
            $table->string("address")->nullable();
            $table->string("city")->nullable();
            $table->foreignUuid("estate_id")->nullable()->constrained("estates");
            $table->foreignUuid("country_id")->nullable()->constrained("countries");
            $table->string("message")->nullable();
            $table->string("referee_code")->nullable(); // create referee code table

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
