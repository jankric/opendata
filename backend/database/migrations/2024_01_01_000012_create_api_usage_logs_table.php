<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint');
            $table->string('method');
            $table->integer('status_code');
            $table->float('response_time')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('requested_at');
            $table->timestamps();

            // Indexes
            $table->index(['endpoint']);
            $table->index(['user_id']);
            $table->index(['requested_at']);
            $table->index(['status_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_usage_logs');
    }
};