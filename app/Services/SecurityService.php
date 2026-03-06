<?php

namespace App\Services;

use App\Models\LoginAttempt;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;

class SecurityService
{
    /**
     * List of disposable/invalid email domains to block.
     */
    protected static array $blockedDomains = [
        'tempmail.com', 'throwaway.email', 'guerrillamail.com', 'mailinator.com',
        'yopmail.com', 'trashmail.com', 'tempinbox.com', 'fakeinbox.com',
        'sharklasers.com', 'guerrillamailblock.com', 'grr.la', 'dispostable.com',
        'maildrop.cc', 'temp-mail.org', 'emailondeck.com', 'getairmail.com',
        'mailcatch.com', 'mintemail.com', 'mohmal.com', 'mytemp.email',
        'tempr.email', 'discard.email', 'mailnesia.com', 'spamgourmet.com',
    ];

    /**
     * Validate if an email domain is acceptable.
     */
    public static function isEmailDomainValid(string $email): bool
    {
        $domain = strtolower(substr(strrchr($email, '@'), 1));

        // Check against blocked domains
        $customBlocked = SiteSetting::get('blocked_email_domains', '');
        $allBlocked = array_merge(
            static::$blockedDomains,
            array_filter(array_map('trim', explode("\n", $customBlocked)))
        );

        if (in_array($domain, $allBlocked)) {
            return false;
        }

        // Check if domain has valid MX record
        if (!checkdnsrr($domain, 'MX') && !checkdnsrr($domain, 'A')) {
            return false;
        }

        return true;
    }

    /**
     * Log a login attempt.
     */
    public static function logLoginAttempt(Request $request, bool $successful, string $guard = 'web'): void
    {
        LoginAttempt::logAttempt(
            $request->input('email', ''),
            $request->ip(),
            $request->userAgent(),
            $successful,
            $guard
        );
    }

    /**
     * Track user login metadata.
     */
    public static function trackUserLogin(User $user, Request $request): void
    {
        $user->update([
            'last_ip' => $request->ip(),
            'last_user_agent' => $request->userAgent(),
            'last_login_at' => now(),
            'login_count' => $user->login_count + 1,
            'failed_login_attempts' => 0,
        ]);
    }

    /**
     * Track a failed login attempt for a user.
     */
    public static function trackFailedLogin(string $email): void
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $maxAttempts = (int) SiteSetting::get('max_login_attempts', 5);
            $lockoutMinutes = (int) SiteSetting::get('lockout_duration', 15);

            $user->increment('failed_login_attempts');

            if ($user->failed_login_attempts >= $maxAttempts) {
                $user->update([
                    'locked_until' => now()->addMinutes($lockoutMinutes),
                ]);
            }
        }
    }

    /**
     * Get security statistics for admin dashboard.
     */
    public static function getStats(): array
    {
        return [
            'failed_logins_today' => LoginAttempt::where('successful', false)
                ->whereDate('created_at', today())->count(),
            'successful_logins_today' => LoginAttempt::where('successful', true)
                ->whereDate('created_at', today())->count(),
            'failed_admin_logins_today' => LoginAttempt::where('successful', false)
                ->where('guard', 'admin')
                ->whereDate('created_at', today())->count(),
            'banned_users' => User::banned()->count(),
            'locked_users' => User::where('locked_until', '>', now())->count(),
            'unique_ips_today' => LoginAttempt::whereDate('created_at', today())
                ->distinct('ip_address')->count('ip_address'),
        ];
    }
}
