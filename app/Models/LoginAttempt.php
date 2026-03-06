<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $fillable = ['email', 'guard', 'ip_address', 'user_agent', 'successful'];

    protected function casts(): array
    {
        return [
            'successful' => 'boolean',
        ];
    }

    /**
     * Check if an IP is currently rate limited.
     */
    public static function isRateLimited(string $ip, int $maxAttempts = 5, int $decayMinutes = 15, string $guard = 'web'): bool
    {
        $count = static::where('ip_address', $ip)
            ->where('guard', $guard)
            ->where('successful', false)
            ->where('created_at', '>=', now()->subMinutes($decayMinutes))
            ->count();

        return $count >= $maxAttempts;
    }

    /**
     * Log a login attempt.
     */
    public static function logAttempt(string $email, string $ip, ?string $userAgent, bool $successful, string $guard = 'web'): void
    {
        static::create([
            'email' => $email,
            'guard' => $guard,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'successful' => $successful,
        ]);
    }

    /**
     * Clean up old attempts (run via scheduler).
     */
    public static function cleanup(int $days = 30): int
    {
        return static::where('created_at', '<', now()->subDays($days))->delete();
    }
}
