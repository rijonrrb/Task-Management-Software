<?php

namespace App\Models;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  MODEL: User                                                 ║
 * ║  Purpose: Authenticated user who owns tasks                  ║
 * ║  Learning: Auth model, relationships, helper methods         ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_banned',
        'ban_reason',
        'banned_at',
        'last_ip',
        'last_user_agent',
        'last_login_at',
        'login_count',
        'failed_login_attempts',
        'locked_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
            'banned_at' => 'datetime',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────
    // RELATIONSHIPS
    // ──────────────────────────────────────────────

    /**
     * A user has many tasks.
     * Usage: $user->tasks → Collection of Task models
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // ──────────────────────────────────────────────
    // HELPER METHODS
    // ──────────────────────────────────────────────

    /**
     * Get the user's first name (before the space).
     * Usage: $user->first_name
     */
    public function getFirstNameAttribute(): string
    {
        return explode(' ', $this->name)[0];
    }

    /**
     * Get user's initials for avatar display.
     * Usage: $user->initials → "JD" (for "John Doe")
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }

    public function isBanned(): bool
    {
        return (bool) $this->is_banned;
    }

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    // ── Additional Relationships ──

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function ticketMessages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }

    // ── Scopes ──

    public function scopeBanned(Builder $query): Builder
    {
        return $query->where('is_banned', true);
    }
}
