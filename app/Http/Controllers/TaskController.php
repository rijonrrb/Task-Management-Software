<?php

namespace App\Http\Controllers;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  CONTROLLER: TaskController                                  ║
 * ║  Purpose: Full CRUD for tasks with Redis caching & events    ║
 * ║  Learning: Resource controller, caching, broadcasting,       ║
 * ║            form requests, eager loading, cache invalidation  ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

use App\Models\Task;
use App\Models\Category;
use App\Events\TaskCreated;
use App\Events\TaskStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    // ──────────────────────────────────────────────
    // INDEX: List all tasks for the logged-in user
    // ──────────────────────────────────────────────

    /**
     * Display a listing of tasks with filters.
     * Route: GET /tasks
     *
     * 🔴 REDIS LEARNING: We cache the full task list per user.
     * Cache key includes filter params so different filters = different cache.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Build a unique cache key based on filters
        $cacheKey = "tasks_{$user->id}_" . md5($request->fullUrl());

        $tasks = Cache::remember($cacheKey, 180, function () use ($user, $request) {
            $query = $user->tasks()->with('category');

            // Apply optional filters
            if ($request->filled('status')) {
                $query->status($request->status);
            }

            if ($request->filled('priority')) {
                $query->priority($request->priority);
            }

            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Sort: default by created_at descending
            $sortBy = $request->get('sort', 'created_at');
            $sortDir = $request->get('direction', 'desc');
            $query->orderBy($sortBy, $sortDir);

            return $query->paginate(10)->withQueryString();
        });

        $categories = Cache::remember('all_categories', 600, function () {
            return Category::all();
        });

        return view('tasks.index', compact('tasks', 'categories'));
    }

    // ──────────────────────────────────────────────
    // CREATE: Show form to create a new task
    // ──────────────────────────────────────────────

    /**
     * Show the form for creating a new task.
     * Route: GET /tasks/create
     */
    public function create()
    {
        $categories = Category::all();
        return view('tasks.create', compact('categories'));
    }

    // ──────────────────────────────────────────────
    // STORE: Save a new task to the database
    // ──────────────────────────────────────────────

    /**
     * Store a newly created task.
     * Route: POST /tasks
     *
     * 🔴 REDIS LEARNING: After creating, we CLEAR the cache
     * so the next request gets fresh data from the database.
     *
     * 🟢 PUSHER LEARNING: We fire a TaskCreated event
     * which broadcasts in real-time to all connected clients.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'priority'    => ['required', 'in:low,medium,high,urgent'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'due_date'    => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        // Create the task for the logged-in user
        $task = Auth::user()->tasks()->create($validated);

        // Load the relationship for the event
        $task->load('category', 'user');

        // 🔴 Clear cached data so dashboard/list shows the new task
        $this->clearUserCache();

        // 🟢 Fire real-time event (Pusher will broadcast this)
        broadcast(new TaskCreated($task))->toOthers();

        return redirect()->route('tasks.show', $task)
                         ->with('success', 'Task created successfully! 🎉');
    }

    // ──────────────────────────────────────────────
    // SHOW: Display a single task
    // ──────────────────────────────────────────────

    /**
     * Display the specified task.
     * Route: GET /tasks/{task}
     */
    public function show(Task $task)
    {
        // Security: Ensure the user owns this task
        $this->authorizeTask($task);

        // Eager-load relationships
        $task->load('category', 'user');

        return view('tasks.show', compact('task'));
    }

    // ──────────────────────────────────────────────
    // EDIT: Show form to edit an existing task
    // ──────────────────────────────────────────────

    /**
     * Show the form for editing the task.
     * Route: GET /tasks/{task}/edit
     */
    public function edit(Task $task)
    {
        $this->authorizeTask($task);

        $categories = Category::all();

        return view('tasks.edit', compact('task', 'categories'));
    }

    // ──────────────────────────────────────────────
    // UPDATE: Save changes to an existing task
    // ──────────────────────────────────────────────

    /**
     * Update the specified task.
     * Route: PUT /tasks/{task}
     */
    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'priority'    => ['required', 'in:low,medium,high,urgent'],
            'status'      => ['required', 'in:pending,in_progress,completed,cancelled'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'due_date'    => ['nullable', 'date'],
        ]);

        // Track if status changed for broadcasting
        $oldStatus = $task->status;

        // If status changed to completed, set completed_at timestamp
        if ($validated['status'] === 'completed' && $oldStatus !== 'completed') {
            $validated['completed_at'] = now();
        }

        // If status changed FROM completed, clear completed_at
        if ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        $task->update($validated);
        $task->load('category', 'user');

        // 🔴 Clear cache
        $this->clearUserCache();

        // 🟢 Broadcast status change event if status actually changed
        if ($oldStatus !== $task->status) {
            broadcast(new TaskStatusChanged($task, $oldStatus))->toOthers();
        }

        return redirect()->route('tasks.show', $task)
                         ->with('success', 'Task updated successfully!');
    }

    // ──────────────────────────────────────────────
    // DESTROY: Delete a task
    // ──────────────────────────────────────────────

    /**
     * Remove the specified task.
     * Route: DELETE /tasks/{task}
     */
    public function destroy(Task $task)
    {
        $this->authorizeTask($task);

        $task->delete();

        // 🔴 Clear cache
        $this->clearUserCache();

        return redirect()->route('tasks.index')
                         ->with('success', 'Task deleted successfully!');
    }

    // ──────────────────────────────────────────────
    // API: Quick status update (used by Vue component)
    // ──────────────────────────────────────────────

    /**
     * Update just the status of a task (AJAX endpoint).
     * Route: PATCH /api/tasks/{task}/status
     *
     * 🟢 PUSHER LEARNING: This endpoint is called from Vue.js
     * and broadcasts the change in real-time.
     */
    public function updateStatus(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
        ]);

        $oldStatus = $task->status;

        if ($validated['status'] === 'completed') {
            $task->markAsCompleted();
        } else {
            $task->update($validated);
        }

        $task->load('category', 'user');

        // Clear cache & broadcast
        $this->clearUserCache();

        if ($oldStatus !== $task->status) {
            broadcast(new TaskStatusChanged($task, $oldStatus))->toOthers();
        }

        return response()->json([
            'success' => true,
            'task'    => $task,
            'message' => 'Status updated to ' . $task->status,
        ]);
    }

    // ──────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────

    /**
     * Ensure the authenticated user owns this task.
     * Aborts with 403 if not authorized.
     */
    private function authorizeTask(Task $task): void
    {
        if ($task->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this task.');
        }
    }

    /**
     * Clear all cached data for the current user.
     *
     * 🔴 REDIS LEARNING: Cache invalidation strategy.
     * When data changes, we must clear stale cache.
     * We use Cache::forget() to remove specific keys.
     */
    private function clearUserCache(): void
    {
        $userId = Auth::id();

        // Clear dashboard stats
        Cache::forget("dashboard_stats_{$userId}");

        // Clear recent tasks
        Cache::forget("recent_tasks_{$userId}");

        // Clear categories count
        Cache::forget('all_categories');

        /**
         * Note: We can't easily clear ALL task list caches
         * (because they include filter params in the key).
         * In production, you'd use Cache Tags for this:
         *
         * Cache::tags(['tasks', "user_{$userId}"])->flush();
         *
         * But Redis cache tags require the Redis driver.
         */
    }
}
