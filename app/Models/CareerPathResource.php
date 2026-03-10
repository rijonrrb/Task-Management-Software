<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerPathResource extends Model
{
    protected $fillable = [
        'career_path_task_id',
        'type',
        'title',
        'url',
        'description',
        'provider',
        'is_free',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_free' => 'boolean',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(CareerPathTask::class, 'career_path_task_id');
    }

    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'video'         => '🎬',
            'article'       => '📄',
            'course'        => '🎓',
            'book'          => '📚',
            'tool'          => '🔧',
            'documentation' => '📖',
            'link'          => '🔗',
            default         => '📎',
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'video'         => 'red',
            'article'       => 'blue',
            'course'        => 'purple',
            'book'          => 'amber',
            'tool'          => 'emerald',
            'documentation' => 'cyan',
            'link'          => 'indigo',
            default         => 'slate',
        };
    }
}
