<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_paths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('target_role');                // e.g. "Full-Stack Developer"
            $table->string('current_level')->default('beginner'); // beginner, intermediate, advanced
            $table->string('target_level')->default('advanced');
            $table->enum('source', ['manual', 'ai'])->default('manual');
            $table->enum('status', ['active', 'paused', 'completed', 'archived'])->default('active');
            $table->integer('estimated_weeks')->nullable();
            $table->date('start_date')->nullable();
            $table->date('target_date')->nullable();
            $table->date('completed_at')->nullable();
            $table->json('tags')->nullable();              // ["php", "laravel", "vue"]
            $table->json('metadata')->nullable();          // AI config, extra settings
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_paths');
    }
};
