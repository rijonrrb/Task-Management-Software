<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\SecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SecurityController extends Controller
{
    public function index()
    {
        $settings = [
            'max_login_attempts' => SiteSetting::get('max_login_attempts', 5),
            'lockout_duration' => SiteSetting::get('lockout_duration', 15),
            'registration_enabled' => SiteSetting::get('registration_enabled', '1'),
            'blocked_email_domains' => SiteSetting::get('blocked_email_domains', ''),
            'require_email_verification' => SiteSetting::get('require_email_verification', '0'),
            'min_password_length' => SiteSetting::get('min_password_length', 8),
            'session_lifetime' => SiteSetting::get('session_lifetime', 120),
            'force_https' => SiteSetting::get('force_https', '0'),
            'blocked_ips' => SiteSetting::get('blocked_ips', ''),
        ];

        $stats = SecurityService::getStats();

        $recentAttempts = LoginAttempt::where('successful', false)
            ->latest()
            ->take(20)
            ->get();

        $suspiciousIps = LoginAttempt::where('successful', false)
            ->where('created_at', '>=', now()->subDay())
            ->select('ip_address')
            ->selectRaw('count(*) as attempt_count')
            ->groupBy('ip_address')
            ->having('attempt_count', '>=', 3)
            ->orderByDesc('attempt_count')
            ->get();

        return view('admin.security.index', compact('settings', 'stats', 'recentAttempts', 'suspiciousIps'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'max_login_attempts' => ['required', 'integer', 'min:3', 'max:20'],
            'lockout_duration' => ['required', 'integer', 'min:5', 'max:120'],
            'registration_enabled' => ['boolean'],
            'blocked_email_domains' => ['nullable', 'string'],
            'require_email_verification' => ['boolean'],
            'min_password_length' => ['required', 'integer', 'min:6', 'max:30'],
            'session_lifetime' => ['required', 'integer', 'min:15', 'max:1440'],
            'force_https' => ['boolean'],
            'blocked_ips' => ['nullable', 'string'],
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::set($key, $value, 'security');
        }

        Cache::forget('site_settings');
        Cache::forget('site_settings_security');

        return back()->with('success', 'Security settings updated.');
    }

    public function blockIp(Request $request)
    {
        $request->validate(['ip' => 'required|ip']);

        $currentBlocked = SiteSetting::get('blocked_ips', '');
        $ips = array_filter(array_map('trim', explode("\n", $currentBlocked)));

        if (!in_array($request->ip, $ips)) {
            $ips[] = $request->ip;
            SiteSetting::set('blocked_ips', implode("\n", $ips), 'security', 'textarea');
        }

        Cache::forget('site_settings');

        return back()->with('success', "IP {$request->ip} has been blocked.");
    }

    public function unlockUser(User $user)
    {
        $user->update([
            'locked_until' => null,
            'failed_login_attempts' => 0,
        ]);

        return back()->with('success', "{$user->name}'s account has been unlocked.");
    }

    public function clearLoginAttempts()
    {
        $deleted = LoginAttempt::cleanup(0); // Clear all
        return back()->with('success', "Cleared {$deleted} login attempt records.");
    }
}
