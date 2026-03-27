<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('prompt_slug')->nullable();          // Which ai_prompt was used
            $table->string('service');                           // career_path, project_management
            $table->string('model');                             // gpt-4o-mini, gpt-4o, etc.
            $table->longText('system_prompt');
            $table->longText('user_prompt');
            $table->longText('response')->nullable();           // Full AI response JSON
            $table->integer('prompt_tokens')->nullable();
            $table->integer('completion_tokens')->nullable();
            $table->integer('total_tokens')->nullable();
            $table->decimal('cost', 10, 6)->nullable();         // Estimated cost in USD
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->integer('duration_ms')->nullable();          // How long the API call took
            $table->json('metadata')->nullable();                // Extra data (career_path_id, etc.)
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('service');
            $table->index('prompt_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
    }
};
