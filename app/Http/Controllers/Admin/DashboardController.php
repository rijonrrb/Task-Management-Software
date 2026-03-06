<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CustomPage;
use App\Models\LoginAttempt;
use App\Models\SupportTicket;
use App\Models\Task;
use App\Models\User;
use App\Services\SecurityService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin_dashboard_stats', 120, function () {
            return [
                'total_users' => User::count(),
                'new_users_today' => User::whereDate('created_at', today())->count(),
                'new_users_week' => User::where('created_at', '>=', now()->subWeek())->count(),
                'total_tasks' => Task::count(),
                'pending_tasks' => Task::where('status', 'pending')->count(),
                'completed_tasks' => Task::where('status', 'completed')->count(),
                'total_categories' => Category::count(),
                'open_tickets' => SupportTicket::open()->count(),
                'total_tickets' => SupportTicket::count(),
                'published_pages' => CustomPage::published()->count(),
                'banned_users' => User::banned()->count(),
            ];
        });

        $securityStats = SecurityService::getStats();

        $recentUsers = User::latest()->take(5)->get();
        $recentTickets = SupportTicket::with('user')->latest()->take(5)->get();

        // User registration trend (last 30 days)
        $userTrend = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Task completion trend
        $taskTrend = Task::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats', 'securityStats', 'recentUsers', 'recentTickets', 'userTrend', 'taskTrend'
        ));
    }
}
