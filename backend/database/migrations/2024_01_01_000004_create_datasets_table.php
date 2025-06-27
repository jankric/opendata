<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('notes')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->foreignId('organization_id')->constrained()->onDelete('restrict');
            $table->string('license')->default('CC-BY-4.0');
            $table->enum('status', ['draft', 'review', 'published', 'archived'])->default('draft');
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->boolean('featured')->default(false);
            $table->jsonb('tags')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->jsonb('schema')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status']);
            $table->index(['visibility']);
            $table->index(['featured']);
            $table->index(['published_at']);
            $table->index(['category_id']);
            $table->index(['organization_id']);
            $table->index(['created_by']);
            
            // Full-text search index
            $table->index(['title', 'description'], 'datasets_search_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};