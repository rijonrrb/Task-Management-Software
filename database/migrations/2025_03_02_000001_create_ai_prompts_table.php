<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_prompts', function (Blueprint $table) {
            $table->id();
            $table->string('name');                             // "Career Path Generator"
            $table->string('slug')->unique();                   // "career-path-generator"
            $table->string('service')->default('career_path');  // career_path, project_management, etc.
            $table->longText('system_prompt');                   // System instructions for AI
            $table->longText('user_prompt_template');            // Template with {placeholders}
            $table->string('model')->default('gpt-4o-mini');    // OpenAI model name
            $table->integer('max_tokens')->default(4000);
            $table->decimal('temperature', 3, 2)->default(0.7);
            $table->json('metadata')->nullable();               // Extra config
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('service');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_prompts');
    }
};
