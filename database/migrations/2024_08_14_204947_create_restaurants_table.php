<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->softDeletes();

            $table->timestamps();
        });

        Schema::create('billing_information', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('restaurant_id')->index();
            $table->string('owner_name');
            $table->string('owner_lastname');
            $table->string('address');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->string('zip_code');
            $table->string('phoneNumber')->index();
            $table->string('email')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_information');
        Schema::dropIfExists('restaurants');
    }
};
