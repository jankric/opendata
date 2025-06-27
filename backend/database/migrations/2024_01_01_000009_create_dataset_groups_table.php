<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dataset_groups', function (Blueprint $table) {
            $table->foreignId('dataset_id')->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Primary key
            $table->primary(['dataset_id', 'group_id']);

            // Indexes
            $table->index(['dataset_id']);
            $table->index(['group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dataset_groups');
    }
};