<?php

namespace App\Models;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  MODEL: Category                                             ║
 * ║  Purpose: Represents a task category (Work, Personal, etc.)  ║
 * ║  Learning: Eloquent relationships, slug generation           ║
 * ╚══════════════════════════════════════════════════════════════╝
 *
 * @property int    $id
 * @property string $name
 * @property string $slug
 * @property string $color
 * @property string $description
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    /**
     * Mass-assignable attributes.
     * These fields can be filled using Category::create([...])
     */
    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
    ];

    // ──────────────────────────────────────────────
    // RELATIONSHIPS
    // ──────────────────────────────────────────────

    /**
     * A category has many tasks.
     * Example: "Work" category → [Task1, Task2, Task3]
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // ──────────────────────────────────────────────
    // AUTO SLUG GENERATION
    // ──────────────────────────────────────────────

    /**
     * Boot method — runs when the model is being used.
     * Automatically generates a URL-friendly slug from the name.
     */
    protected static function boot(): void
    {
        parent::boot();

        // Before creating a new category, auto-generate slug
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // ──────────────────────────────────────────────
    // HELPER METHODS
    // ──────────────────────────────────────────────

    /**
     * Get the count of tasks in this category.
     */
    public function taskCount(): int
    {
        return $this->tasks()->count();
    }
}
