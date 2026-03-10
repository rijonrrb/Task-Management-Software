<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_path_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_path_task_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['link', 'video', 'article', 'course', 'book', 'tool', 'documentation', 'other'])->default('link');
            $table->string('title');
            $table->string('url');
            $table->text('description')->nullable();
            $table->string('provider')->nullable();        // e.g. "YouTube", "Udemy", "MDN"
            $table->boolean('is_free')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('career_path_task_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_path_resources');
    }
};
