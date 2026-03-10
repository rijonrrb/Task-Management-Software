<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_path_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_path_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('career_path_tasks')->cascadeOnDelete();

            // Task content
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();           // Rich text / markdown content
            $table->tinyInteger('depth')->default(0);       // 0 = main, 1 = subtask, 2 = sub-subtask
            $table->integer('sort_order')->default(0);

            // Scheduling
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'skipped'])->default('not_started');
            $table->integer('estimated_hours')->nullable();
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Learning content
            $table->string('video_url')->nullable();
            $table->string('video_type')->nullable();       // youtube, vimeo, upload
            $table->string('video_thumbnail')->nullable();
            $table->integer('duration_minutes')->nullable(); // estimated learning duration

            // Additional fields for AI compatibility
            $table->enum('source', ['manual', 'ai'])->default('manual');
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['career_path_id', 'parent_id', 'sort_order']);
            $table->index(['user_id', 'status']);
            $table->index('depth');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_path_tasks');
    }
};
