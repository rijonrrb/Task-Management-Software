<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BannedUserCheck
{
    /**
     * Reject banned users from accessing the application.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->isBanned()) {
            $reason = Auth::guard('web')->user()->ban_reason ?? 'Your account has been suspended.';
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', $reason);
        }

        return $next($request);
    }
}
