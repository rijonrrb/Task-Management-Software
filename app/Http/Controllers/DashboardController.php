<?php

namespace App\Http\Controllers;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  CONTROLLER: DashboardController                             ║
 * ║  Purpose: Main dashboard with stats, cached via Redis        ║
 * ║  Learning: Redis caching, aggregation queries, cache tags    ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

use App\Models\Task;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with task statistics.
     * Route: GET /dashboard
     *
     * 🔴 REDIS LEARNING POINT:
     * We cache dashboard stats in Redis for 5 minutes.
     * This means if 100 users hit the dashboard, the database
     * query only runs ONCE every 5 minutes instead of 100 times!
     */
    public function index()
    {
        $user = Auth::user();

        /**
         * Cache::remember() explained:
         * - Key: "dashboard_stats_{user_id}" — unique per user
         * - TTL: 300 seconds (5 minutes)
         * - Closure: Only runs if cache is EMPTY or EXPIRED
         *
         * First visit:  Runs the queries, stores result in Redis
         * Next visits:  Returns cached data instantly (no DB hit!)
         */
        $stats = Cache::remember("dashboard_stats_{$user->id}", 300, function () use ($user) {
            return [
                'total_tasks'     => $user->tasks()->count(),
                'pending_tasks'   => $user->tasks()->status('pending')->count(),
                'in_progress'     => $user->tasks()->status('in_progress')->count(),
                'completed_tasks' => $user->tasks()->status('completed')->count(),
                'overdue_tasks'   => $user->tasks()->overdue()->count(),
                'urgent_tasks'    => $user->tasks()->priority('urgent')
                                         ->whereNotIn('status', ['completed', 'cancelled'])
                                         ->count(),
            ];
        });

        // Get recent tasks (also cached for 2 minutes)
        $recentTasks = Cache::remember("recent_tasks_{$user->id}", 120, function () use ($user) {
            return $user->tasks()
                        ->with('category')          // Eager-load category (prevents N+1 problem)
                        ->latest()                   // Order by newest first
                        ->take(5)                    // Only get 5 latest
                        ->get();
        });

        // Get all categories for the sidebar/filter
        $categories = Cache::remember('all_categories', 600, function () {
            return Category::withCount('tasks')->get();
        });

        return view('dashboard', compact('stats', 'recentTasks', 'categories'));
    }
}
