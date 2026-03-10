<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CareerPathTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'career_path_id',
        'user_id',
        'parent_id',
        'title',
        'description',
        'content',
        'depth',
        'sort_order',
        'priority',
        'status',
        'estimated_hours',
        'start_date',
        'due_date',
        'completed_at',
        'video_url',
        'video_type',
        'video_thumbnail',
        'duration_minutes',
        'source',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'start_date'    => 'date',
            'due_date'      => 'date',
            'completed_at'  => 'datetime',
            'metadata'      => 'array',
        ];
    }

    // ── Relationships ──

    public function careerPath(): BelongsTo
    {
        return $this->belongsTo(CareerPath::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CareerPathTask::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CareerPathTask::class, 'parent_id')->orderBy('sort_order');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(CareerPathResource::class)->orderBy('sort_order');
    }

    public function keywords(): HasMany
    {
        return $this->hasMany(CareerPathKeyword::class)->orderBy('sort_order');
    }

    // ── Scopes ──

    public function scopeMainTasks($query)
    {
        return $query->whereNull('parent_id')->where('depth', 0);
    }

    public function scopeSubTasks($query)
    {
        return $query->where('depth', 1);
    }

    public function scopeSubSubTasks($query)
    {
        return $query->where('depth', 2);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // ── Accessors ──

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && !in_array($this->status, ['completed', 'skipped']);
    }

    public function getProgressAttribute(): int
    {
        if ($this->depth === 2) {
            return $this->status === 'completed' ? 100 : ($this->status === 'in_progress' ? 50 : 0);
        }

        $children = $this->children;
        if ($children->isEmpty()) {
            return $this->status === 'completed' ? 100 : ($this->status === 'in_progress' ? 50 : 0);
        }

        $total = $children->count();
        $completed = $children->where('status', 'completed')->count();
        return (int) round(($completed / $total) * 100);
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => 'red',
            'high'   => 'orange',
            'medium' => 'blue',
            'low'    => 'slate',
            default  => 'slate',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'completed'   => 'emerald',
            'in_progress' => 'amber',
            'not_started' => 'blue',
            'skipped'     => 'slate',
            default       => 'slate',
        };
    }

    public function getDepthLabelAttribute(): string
    {
        return match ($this->depth) {
            0 => 'Main Task',
            1 => 'Subtask',
            2 => 'Sub-subtask',
            default => 'Task',
        };
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) return null;

        if ($this->video_type === 'youtube' || str_contains($this->video_url, 'youtube.com') || str_contains($this->video_url, 'youtu.be')) {
            $videoId = $this->extractYouTubeId($this->video_url);
            return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
        }

        if ($this->video_type === 'vimeo' || str_contains($this->video_url, 'vimeo.com')) {
            preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $matches);
            return isset($matches[1]) ? "https://player.vimeo.com/video/{$matches[1]}" : null;
        }

        return $this->video_url;
    }

    // ── Helpers ──

    public function markAsCompleted(): bool
    {
        return $this->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function markAsInProgress(): bool
    {
        return $this->update([
            'status'     => 'in_progress',
            'start_date' => $this->start_date ?? now(),
        ]);
    }

    private function extractYouTubeId(string $url): ?string
    {
        $pattern = '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }
}
