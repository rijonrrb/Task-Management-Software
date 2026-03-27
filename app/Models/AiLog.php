<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiLog extends Model
{
    protected $fillable = [
        'user_id',
        'prompt_slug',
        'service',
        'model',
        'system_prompt',
        'user_prompt',
        'response',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'cost',
        'status',
        'error_message',
        'duration_ms',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'cost'     => 'float',
        ];
    }

    // ── Relationships ──

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ──

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForService($query, string $service)
    {
        return $query->where('service', $service);
    }
}
