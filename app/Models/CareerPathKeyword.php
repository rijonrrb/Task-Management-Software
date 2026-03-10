<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerPathKeyword extends Model
{
    protected $fillable = [
        'career_path_task_id',
        'keyword',
        'definition',
        'importance',
        'sort_order',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(CareerPathTask::class, 'career_path_task_id');
    }

    public function getImportanceColorAttribute(): string
    {
        return match ($this->importance) {
            'essential'    => 'red',
            'important'    => 'amber',
            'good_to_know' => 'blue',
            default        => 'slate',
        };
    }

    public function getImportanceLabelAttribute(): string
    {
        return match ($this->importance) {
            'essential'    => 'Essential',
            'important'    => 'Important',
            'good_to_know' => 'Good to Know',
            default        => 'Other',
        };
    }
}
