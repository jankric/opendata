<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();
            $table->string('query');
            $table->integer('result_count')->default(0);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('searched_at');
            $table->timestamps();

            // Indexes
            $table->index(['query']);
            $table->index(['user_id']);
            $table->index(['searched_at']);
            $table->index(['ip_address']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_logs');
    }
};