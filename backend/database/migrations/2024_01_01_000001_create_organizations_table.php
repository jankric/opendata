<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->nullable(); // OPD, Dinas, Badan, etc.
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('logo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['is_active']);
            $table->index(['type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};