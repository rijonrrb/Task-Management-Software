<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CareerPath extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'target_role',
        'current_level',
        'target_level',
        'source',
        'status',
        'estimated_weeks',
        'start_date',
        'target_date',
        'completed_at',
        'tags',
        'metadata',
        'sort_order',
        'is_pinned',
        'pinned_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date'   => 'date',
            'target_date'  => 'date',
            'completed_at' => 'date',
            'tags'         => 'array',
            'metadata'     => 'array',
            'is_pinned'    => 'boolean',
            'pinned_at'    => 'datetime',
        ];
    }

    // ── Relationships ──

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(CareerPathTask::class)->orderBy('sort_order');
    }

    public function mainTasks(): HasMany
    {
        return $this->hasMany(CareerPathTask::class)
            ->whereNull('parent_id')
            ->where('depth', 0)
            ->orderBy('sort_order');
    }

    // ── Scopes ──

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeManual($query)
    {
        return $query->where('source', 'manual');
    }

    public function scopeAi($query)
    {
        return $query->where('source', 'ai');
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    // ── Accessors ──

    public function getProgressAttribute(): int
    {
        $total = $this->tasks()->count();
        if ($total === 0) return 0;
        $completed = $this->tasks()->where('status', 'completed')->count();
        return (int) round(($completed / $total) * 100);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->target_date
            && $this->target_date->isPast()
            && $this->status !== 'completed';
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'emerald',
            'paused'    => 'amber',
            'completed' => 'blue',
            'archived'  => 'slate',
            default     => 'slate',
        };
    }

    // ── Boot ──

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (CareerPath $cp) {
            if (empty($cp->slug)) {
                $base = Str::slug($cp->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $cp->slug = $slug;
            }
        });
    }

    // ── Helpers ──

    public function markAsCompleted(): bool
    {
        return $this->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);
    }
}
