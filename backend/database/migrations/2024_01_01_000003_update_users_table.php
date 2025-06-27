<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new columns
            $table->string('phone')->nullable()->after('email');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('set null')->after('phone');
            $table->string('position')->nullable()->after('organization_id');
            $table->string('avatar_url')->nullable()->after('position');
            $table->boolean('is_active')->default(true)->after('avatar_url');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->jsonb('metadata')->nullable()->after('last_login_at');
            $table->softDeletes()->after('updated_at');

            // Indexes
            $table->index(['is_active']);
            $table->index(['organization_id']);
            $table->index(['last_login_at']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'phone',
                'organization_id',
                'position',
                'avatar_url',
                'is_active',
                'last_login_at',
                'metadata'
            ]);
        });
    }
};