<?php

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  MIGRATION: Tasks Table                                      ║
 * ║  Purpose: Core table for the task management system          ║
 * ║  Learning: Foreign keys, enums, indexes, nullable columns    ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the 'tasks' table — the heart of our application.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();                                              // Auto-increment PK

            // ── Relationship columns ────────────────────────────────
            $table->foreignId('user_id')                               // Who owns this task?
                  ->constrained()                                      // References users.id
                  ->onDelete('cascade');                                // Delete tasks if user deleted

            $table->foreignId('category_id')                           // Which category?
                  ->nullable()                                         // Tasks can be uncategorized
                  ->constrained()                                      // References categories.id
                  ->onDelete('set null');                               // Keep task if category deleted

            // ── Task data columns ───────────────────────────────────
            $table->string('title');                                    // Task title
            $table->text('description')->nullable();                   // Detailed description
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])
                  ->default('medium');                                 // How important?
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])
                  ->default('pending');                                // Current state
            $table->date('due_date')->nullable();                      // When is it due?
            $table->timestamp('completed_at')->nullable();             // When was it finished?

            // ── Indexes for faster queries ──────────────────────────
            $table->index('status');                                   // Filter by status quickly
            $table->index('priority');                                 // Filter by priority quickly
            $table->index('due_date');                                 // Sort by due date quickly

            $table->timestamps();                                      // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
