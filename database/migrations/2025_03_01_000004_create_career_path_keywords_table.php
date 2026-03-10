<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_path_keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_path_task_id')->constrained()->cascadeOnDelete();
            $table->string('keyword');
            $table->text('definition')->nullable();
            $table->enum('importance', ['essential', 'important', 'good_to_know'])->default('important');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('career_path_task_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_path_keywords');
    }
};
