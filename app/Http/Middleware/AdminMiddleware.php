<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Ensure the authenticated user is an admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            abort(403, 'Unauthorized. Admin access required.');
        }

        if (!Auth::guard('admin')->user()->isActive()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            return redirect()->route('admin.login')->with('error', 'Your admin account is inactive.');
        }

        return $next($request);
    }
}
