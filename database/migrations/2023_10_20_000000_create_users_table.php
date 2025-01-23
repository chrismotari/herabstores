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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->string('phone');
            $table->string('alt_phone')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('alt_email')->unique()->nullable();
            $table->string('address');
            $table->string('city');
            $table->foreignUuid("state_id")->restrictOnDelete()->constrained('states');
            $table->string('zip_code')->nullable();
            $table->foreignUuid("country_id")->restrictOnDelete()->constrained('countries');
            $table->string('photo_url')->nullable();
            $table->foreignUuid("job_category_id")->nullable()->nullOnDelete()->constrained('job_categories');
            $table->foreignUuid("profession_id")->nullable()->nullOnDelete()->constrained('professions');
            $table->foreignUuid("contract_type_id")->nullable()->nullOnDelete()->constrained('contract_types');
            $table->date('dob')->nullable();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
