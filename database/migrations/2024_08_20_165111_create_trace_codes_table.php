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
        Schema::create('trace_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('trace_code')->unique()->index('trace-code-index');
            $table->string('service')->index('restaurant-service-index');
            $table->string('http_code')->index('restaurant-http-index');
            $table->string('method')->index('restaurant-method-index');
            $table->string('class')->index('restaurant-class-index');
            $table->string('description');
            $table->timestamp('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trace_codes');
    }
};
