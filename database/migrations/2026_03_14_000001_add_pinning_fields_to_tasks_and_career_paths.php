<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('completed_at');
            $table->timestamp('pinned_at')->nullable()->after('is_pinned');
            $table->index(['user_id', 'is_pinned']);
            $table->index('pinned_at');
        });

        Schema::table('career_paths', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('sort_order');
            $table->timestamp('pinned_at')->nullable()->after('is_pinned');
            $table->index(['user_id', 'is_pinned']);
            $table->index('pinned_at');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_pinned']);
            $table->dropIndex(['pinned_at']);
            $table->dropColumn(['is_pinned', 'pinned_at']);
        });

        Schema::table('career_paths', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'is_pinned']);
            $table->dropIndex(['pinned_at']);
            $table->dropColumn(['is_pinned', 'pinned_at']);
        });
    }
};
