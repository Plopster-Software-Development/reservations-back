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
        Schema::create('api_consumer_access_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('consumer_id')->index();
            $table->string('token')->unique();
            $table->enum('status', ['active', 'revoked', 'expired'])->default('active');
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
            $table->foreign('consumer_id')->references('id')->on('api_consumers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_consumer_access_tokens');
    }
};
