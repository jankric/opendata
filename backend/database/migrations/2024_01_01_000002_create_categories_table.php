<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active']);
            $table->index(['parent_id']);
            $table->index(['sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};