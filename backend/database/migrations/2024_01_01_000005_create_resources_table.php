<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['file', 'api', 'link'])->default('file');
            $table->string('format')->nullable(); // csv, json, xlsx, pdf, etc.
            $table->string('url')->nullable();
            $table->string('file_path')->nullable();
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->string('mime_type')->nullable();
            $table->string('encoding')->nullable();
            $table->jsonb('schema')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->boolean('is_preview_available')->default(false);
            $table->jsonb('preview_data')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['dataset_id']);
            $table->index(['type']);
            $table->index(['format']);
            $table->index(['is_preview_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};