<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPrompt extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'service',
        'system_prompt',
        'user_prompt_template',
        'model',
        'max_tokens',
        'temperature',
        'metadata',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'metadata'    => 'array',
            'is_active'   => 'boolean',
            'temperature' => 'float',
        ];
    }

    // ── Scopes ──

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForService($query, string $service)
    {
        return $query->where('service', $service);
    }

    /**
     * Get an active prompt by slug.
     */
    public static function getBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->active()->first();
    }

    /**
     * Build the user prompt by replacing placeholders.
     */
    public function buildUserPrompt(array $variables): string
    {
        $prompt = $this->user_prompt_template;

        foreach ($variables as $key => $value) {
            $prompt = str_replace('{' . $key . '}', $value, $prompt);
        }

        return $prompt;
    }
}
