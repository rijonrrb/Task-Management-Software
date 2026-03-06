<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id', 'subject', 'description', 'status',
        'priority', 'ticket_number', 'assigned_admin_id', 'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }

    // ── Relationships ──

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_admin_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id');
    }

    // ── Scopes ──

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    // ── Helpers ──

    public static function generateTicketNumber(): string
    {
        do {
            $number = 'TK-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        } while (static::where('ticket_number', $number)->exists());

        return $number;
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['open', 'in_progress']);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'open' => 'badge-warning',
            'in_progress' => 'badge-info',
            'resolved' => 'badge-success',
            'closed' => 'badge-secondary',
            default => 'badge-secondary',
        };
    }
}
