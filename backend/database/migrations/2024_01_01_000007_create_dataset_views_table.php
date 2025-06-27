<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dataset_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('viewed_at');
            $table->timestamps();

            // Indexes
            $table->index(['dataset_id']);
            $table->index(['user_id']);
            $table->index(['viewed_at']);
            $table->index(['ip_address']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dataset_views');
    }
};