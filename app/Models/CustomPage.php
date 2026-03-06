<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CustomPage extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'meta_title', 'meta_description',
        'meta_keywords', 'is_published', 'sort_order', 'show_in_menu',
        'menu_position', 'created_by_admin_id',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'show_in_menu' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by_admin_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeInMenu($query, string $position = null)
    {
        $query->where('show_in_menu', true)->where('is_published', true);
        if ($position) {
            $query->whereIn('menu_position', [$position, 'both']);
        }
        return $query->orderBy('sort_order');
    }

    protected static function booted(): void
    {
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }
}
