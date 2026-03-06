<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::withCount(['tasks', 'supportTickets'])->select('users.*');

            return DataTables::eloquent($query)
                ->addColumn('name_html', function (User $user) {
                    $initials = e($user->initials);
                    $name     = e($user->name);
                    $email    = e($user->email);
                    $url      = route('admin.users.show', $user);
                    return '<div class="flex items-center gap-3">'
                        . '<div class="w-9 h-9 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">' . $initials . '</div>'
                        . '<div><a href="' . $url . '" class="font-medium text-gray-700 hover:text-indigo-600 transition">' . $name . '</a>'
                        . '<p class="text-xs text-gray-400">' . $email . '</p></div></div>';
                })
                ->editColumn('last_login_at', function (User $user) {
                    return $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never';
                })
                ->addColumn('status_html', function (User $user) {
                    if ($user->is_banned) {
                        return '<span class="badge badge-danger">Banned</span>';
                    }
                    if ($user->isLocked()) {
                        return '<span class="badge badge-warning">Locked</span>';
                    }
                    return '<span class="badge badge-success">Active</span>';
                })
                ->addColumn('actions', function (User $user) {
                    $viewUrl  = route('admin.users.show', $user);
                    $banUrl   = route('admin.users.toggle-ban', $user);
                    $csrf     = csrf_token();
                    $banLabel = $user->is_banned ? 'Unban' : 'Ban';
                    $banClass = $user->is_banned ? 'btn-admin-success' : 'btn-admin-danger';
                    $formId   = 'ban-form-' . $user->id;
                    $title    = $user->is_banned ? 'Unban User' : 'Ban User';
                    $msg      = $user->is_banned
                        ? 'This will restore access for ' . e($user->name)
                        : 'This will block ' . e($user->name) . ' from accessing the site';

                    return '<div class="flex items-center gap-1">'
                        . '<a href="' . $viewUrl . '" class="btn-admin btn-admin-outline btn-admin-sm" title="View">'
                        . '<svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
                        . '</a>'
                        . '<form method="POST" action="' . $banUrl . '" id="' . $formId . '" class="inline">'
                        . '<input type="hidden" name="_token" value="' . $csrf . '">'
                        . '<button type="button" onclick="confirmAction(\'' . $formId . '\',\'' . $title . '\',\'' . addslashes($msg) . '\')" class="btn-admin btn-admin-sm ' . $banClass . '">' . $banLabel . '</button>'
                        . '</form></div>';
                })
                ->rawColumns(['name_html', 'status_html', 'actions'])
                ->toJson();
        }

        return view('admin.users.index');
    }


    public function show(User $user)
    {
        $user->loadCount(['tasks', 'supportTickets']);

        $taskStats = [
            'total' => $user->tasks()->count(),
            'pending' => $user->tasks()->where('status', 'pending')->count(),
            'in_progress' => $user->tasks()->where('status', 'in_progress')->count(),
            'completed' => $user->tasks()->where('status', 'completed')->count(),
            'cancelled' => $user->tasks()->where('status', 'cancelled')->count(),
        ];

        $recentTasks = $user->tasks()->with('category')->latest()->take(10)->get();
        $tickets = $user->supportTickets()->latest()->take(5)->get();

        // Session data for device info
        $sessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) {
                $session->last_activity_at = \Carbon\Carbon::createFromTimestamp($session->last_activity);
                return $session;
            });

        return view('admin.users.show', compact('user', 'taskStats', 'recentTasks', 'tickets', 'sessions'));
    }

    public function toggleBan(Request $request, User $user)
    {
        if ($user->is_banned) {
            $user->update([
                'is_banned' => false,
                'ban_reason' => null,
                'banned_at' => null,
                'failed_login_attempts' => 0,
                'locked_until' => null,
            ]);
            return back()->with('success', "{$user->name} has been unbanned.");
        }

        $request->validate(['ban_reason' => 'nullable|string|max:500']);

        $user->update([
            'is_banned' => true,
            'ban_reason' => $request->input('ban_reason', 'Account suspended by administrator.'),
            'banned_at' => now(),
        ]);

        return back()->with('success', "{$user->name} has been banned.");
    }

    public function tasks(User $user, Request $request)
    {
        $query = $user->tasks()->with('category');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $tasks = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.tasks', compact('user', 'tasks'));
    }

    public function destroy(User $user)
    {
        $user->delete();
        Cache::forget('admin_dashboard_stats');

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
