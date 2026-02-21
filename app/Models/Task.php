<?php

namespace App\Models;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  MODEL: Task                                                 ║
 * ║  Purpose: Core model — represents a task in the system       ║
 * ║  Learning: Relationships, scopes, accessors, casting         ║
 * ╚══════════════════════════════════════════════════════════════╝
 *
 * @property int         $id
 * @property int         $user_id
 * @property int|null    $category_id
 * @property string      $title
 * @property string|null $description
 * @property string      $priority     (low|medium|high|urgent)
 * @property string      $status       (pending|in_progress|completed|cancelled)
 * @property Carbon|null $due_date
 * @property Carbon|null $completed_at
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    /**
     * Mass-assignable attributes.
     * Security: Only these fields can be filled via create/update.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'completed_at',
    ];

    /**
     * Attribute casting — automatically convert these types.
     * 'due_date' string → Carbon date object
     * 'completed_at' string → Carbon datetime object
     */
    protected function casts(): array
    {
        return [
            'due_date'     => 'date',
            'completed_at' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────
    // RELATIONSHIPS
    // ──────────────────────────────────────────────

    /**
     * A task belongs to a user (the owner).
     * Usage: $task->user->name
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A task belongs to a category (optional).
     * Usage: $task->category->name
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ──────────────────────────────────────────────
    // QUERY SCOPES (reusable query filters)
    // ──────────────────────────────────────────────

    /**
     * Scope: Filter tasks by status.
     * Usage: Task::status('completed')->get()
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter tasks by priority.
     * Usage: Task::priority('high')->get()
     */
    public function scopePriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope: Get overdue tasks (past due date, not completed).
     * Usage: Task::overdue()->get()
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', Carbon::today())
                     ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Scope: Get tasks for a specific user.
     * Usage: Task::forUser($userId)->get()
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ──────────────────────────────────────────────
    // ACCESSORS (computed properties)
    // ──────────────────────────────────────────────

    /**
     * Check if the task is overdue.
     * Usage: $task->is_overdue → true/false
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Get a human-readable priority badge color.
     * Usage: $task->priority_color → "red"
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'red',
            'high'   => 'orange',
            'medium' => 'blue',
            'low'    => 'gray',
            default  => 'gray',
        };
    }

    /**
     * Get a human-readable status badge color.
     * Usage: $task->status_color → "green"
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'completed'   => 'green',
            'in_progress' => 'yellow',
            'pending'     => 'blue',
            'cancelled'   => 'red',
            default       => 'gray',
        };
    }

    // ──────────────────────────────────────────────
    // HELPER METHODS
    // ──────────────────────────────────────────────

    /**
     * Mark this task as completed.
     * Sets status and records the completion timestamp.
     */
    public function markAsCompleted(): bool
    {
        return $this->update([
            'status'       => 'completed',
            'completed_at' => Carbon::now(),
        ]);
    }

    /**
     * Mark this task as in progress.
     */
    public function markAsInProgress(): bool
    {
        return $this->update([
            'status' => 'in_progress',
        ]);
    }
}
