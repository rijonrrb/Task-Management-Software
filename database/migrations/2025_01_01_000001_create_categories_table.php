<?php

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  MIGRATION: Categories Table                                 ║
 * ║  Purpose: Stores task categories (e.g., Work, Personal)      ║
 * ║  Learning: Basic migration with timestamps and soft deletes  ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the 'categories' table for organizing tasks.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();                                    // Auto-increment primary key
            $table->string('name');                          // Category name (e.g., "Work")
            $table->string('slug')->unique();                // URL-friendly name (e.g., "work")
            $table->string('color', 7)->default('#3B82F6');  // Hex color for UI badges
            $table->text('description')->nullable();         // Optional description
            $table->timestamps();                            // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
