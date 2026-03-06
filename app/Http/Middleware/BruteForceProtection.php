<?php

namespace App\Http\Middleware;

use App\Models\LoginAttempt;
use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BruteForceProtection
{
    /**
     * Protect against brute force login attacks.
     */
    public function handle(Request $request, Closure $next, string $guard = 'web'): Response
    {
        if ($request->isMethod('post') && ($request->routeIs('login') || $request->routeIs('admin.login.post'))) {
            $ip = $request->ip();
            $maxAttempts = (int) SiteSetting::get($guard === 'admin' ? 'admin_max_login_attempts' : 'max_login_attempts', 5);
            $decayMinutes = (int) SiteSetting::get('lockout_duration', 15);

            if (LoginAttempt::isRateLimited($ip, $maxAttempts, $decayMinutes, $guard)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => "Too many login attempts. Try again in {$decayMinutes} minutes.",
                    ], 429);
                }

                return back()->withErrors([
                    'email' => "Too many {$guard} login attempts. Please try again in {$decayMinutes} minutes.",
                ])->onlyInput('email');
            }
        }

        return $next($request);
    }
}
