<?php

namespace App\Http\Controllers;

use App\Models\CareerPath;
use App\Models\CareerPathTask;
use App\Models\CareerPathResource;
use App\Models\CareerPathKeyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CareerPathController extends Controller
{
    // ── Index: List all career paths ──

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = CareerPath::forUser($user->id)
            ->withCount('tasks')
            ->with(['tasks' => fn($q) => $q->select('id', 'career_path_id', 'status')]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('target_role', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $careerPaths = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate(12)->withQueryString();

        $counts = [
            'total'     => CareerPath::forUser($user->id)->count(),
            'active'    => CareerPath::forUser($user->id)->where('status', 'active')->count(),
            'completed' => CareerPath::forUser($user->id)->where('status', 'completed')->count(),
            'manual'    => CareerPath::forUser($user->id)->where('source', 'manual')->count(),
            'ai'        => CareerPath::forUser($user->id)->where('source', 'ai')->count(),
        ];

        return view('career-path.index', compact('careerPaths', 'counts'));
    }

    // ── Create: Show form ──

    public function create()
    {
        return view('career-path.create');
    }

    // ── Store: Save new career path ──

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:5000'],
            'target_role'    => ['required', 'string', 'max:255'],
            'current_level'  => ['required', 'in:beginner,intermediate,advanced'],
            'target_level'   => ['required', 'in:beginner,intermediate,advanced'],
            'estimated_weeks'=> ['nullable', 'integer', 'min:1', 'max:520'],
            'start_date'     => ['nullable', 'date'],
            'target_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'tags'           => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['user_id'] = Auth::id();
        $validated['source'] = 'manual';

        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        $careerPath = CareerPath::create($validated);

        $this->clearCache();

        return redirect()->route('career-path.show', $careerPath)
                         ->with('success', 'Career path created successfully! Now add your learning tasks.');
    }

    // ── Show: Display career path with full hierarchy ──

    public function show(CareerPath $careerPath)
    {
        $this->authorizeAccess($careerPath);

        $careerPath->load([
            'mainTasks' => function ($query) {
                $query->withCount('children')
                      ->with([
                          'children' => function ($q) {
                              $q->withCount('children')
                                ->with(['children' => fn($sq) => $sq->orderBy('sort_order')])
                                ->orderBy('sort_order');
                          }
                      ]);
            }
        ]);

        $stats = [
            'total_tasks'  => $careerPath->tasks()->count(),
            'completed'    => $careerPath->tasks()->where('status', 'completed')->count(),
            'in_progress'  => $careerPath->tasks()->where('status', 'in_progress')->count(),
            'not_started'  => $careerPath->tasks()->where('status', 'not_started')->count(),
            'main_tasks'   => $careerPath->tasks()->where('depth', 0)->count(),
            'subtasks'     => $careerPath->tasks()->where('depth', 1)->count(),
            'sub_subtasks' => $careerPath->tasks()->where('depth', 2)->count(),
        ];

        return view('career-path.show', compact('careerPath', 'stats'));
    }

    // ── Edit: Show edit form ──

    public function edit(CareerPath $careerPath)
    {
        $this->authorizeAccess($careerPath);
        return view('career-path.edit', compact('careerPath'));
    }

    // ── Update ──

    public function update(Request $request, CareerPath $careerPath)
    {
        $this->authorizeAccess($careerPath);

        $validated = $request->validate([
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:5000'],
            'target_role'    => ['required', 'string', 'max:255'],
            'current_level'  => ['required', 'in:beginner,intermediate,advanced'],
            'target_level'   => ['required', 'in:beginner,intermediate,advanced'],
            'status'         => ['required', 'in:active,paused,completed,archived'],
            'estimated_weeks'=> ['nullable', 'integer', 'min:1', 'max:520'],
            'start_date'     => ['nullable', 'date'],
            'target_date'    => ['nullable', 'date'],
            'tags'           => ['nullable', 'string', 'max:1000'],
        ]);

        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = null;
        }

        if ($validated['status'] === 'completed' && $careerPath->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        $careerPath->update($validated);
        $this->clearCache();

        return redirect()->route('career-path.show', $careerPath)
                         ->with('success', 'Career path updated successfully!');
    }

    // ── Destroy ──

    public function destroy(CareerPath $careerPath)
    {
        $this->authorizeAccess($careerPath);
        $careerPath->delete();
        $this->clearCache();

        return redirect()->route('career-path.index')
                         ->with('success', 'Career path deleted successfully!');
    }

    // ── Task CRUD ──

    public function storeTask(Request $request, CareerPath $careerPath)
    {
        $this->authorizeAccess($careerPath);

        $validated = $request->validate([
            'parent_id'        => ['nullable', 'exists:career_path_tasks,id'],
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:5000'],
            'content'          => ['nullable', 'string', 'max:50000'],
            'priority'         => ['required', 'in:low,medium,high,urgent'],
            'estimated_hours'  => ['nullable', 'integer', 'min:1', 'max:1000'],
            'start_date'       => ['nullable', 'date'],
            'due_date'         => ['nullable', 'date'],
            'video_url'        => ['nullable', 'url', 'max:500'],
            'video_type'       => ['nullable', 'in:youtube,vimeo,upload,other'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:10000'],
            // Resources (arrays)
            'resources'          => ['nullable', 'array', 'max:20'],
            'resources.*.type'   => ['required_with:resources', 'in:link,video,article,course,book,tool,documentation,other'],
            'resources.*.title'  => ['required_with:resources', 'string', 'max:255'],
            'resources.*.url'    => ['required_with:resources', 'url', 'max:500'],
            'resources.*.description' => ['nullable', 'string', 'max:500'],
            'resources.*.provider'    => ['nullable', 'string', 'max:100'],
            'resources.*.is_free'     => ['nullable', 'boolean'],
            // Keywords (arrays)
            'keywords'               => ['nullable', 'array', 'max:30'],
            'keywords.*.keyword'     => ['required_with:keywords', 'string', 'max:100'],
            'keywords.*.definition'  => ['nullable', 'string', 'max:500'],
            'keywords.*.importance'  => ['nullable', 'in:essential,important,good_to_know'],
        ]);

        // Determine depth
        $depth = 0;
        if ($validated['parent_id']) {
            $parent = CareerPathTask::findOrFail($validated['parent_id']);
            if ($parent->career_path_id !== $careerPath->id) {
                abort(403, 'Parent task does not belong to this career path.');
            }
            if ($parent->depth >= 2) {
                abort(422, 'Maximum nesting depth (3 levels) reached.');
            }
            $depth = $parent->depth + 1;
        }

        // Get next sort order
        $maxSort = CareerPathTask::where('career_path_id', $careerPath->id)
            ->where('parent_id', $validated['parent_id'] ?? null)
            ->max('sort_order') ?? -1;

        DB::beginTransaction();
        try {
            $task = CareerPathTask::create([
                'career_path_id'  => $careerPath->id,
                'user_id'         => Auth::id(),
                'parent_id'       => $validated['parent_id'] ?? null,
                'title'           => $validated['title'],
                'description'     => $validated['description'] ?? null,
                'content'         => $validated['content'] ?? null,
                'depth'           => $depth,
                'sort_order'      => $maxSort + 1,
                'priority'        => $validated['priority'],
                'estimated_hours' => $validated['estimated_hours'] ?? null,
                'start_date'      => $validated['start_date'] ?? null,
                'due_date'        => $validated['due_date'] ?? null,
                'video_url'       => $validated['video_url'] ?? null,
                'video_type'      => $validated['video_type'] ?? null,
                'duration_minutes'=> $validated['duration_minutes'] ?? null,
                'source'          => 'manual',
            ]);

            // Create resources
            if (!empty($validated['resources'])) {
                foreach ($validated['resources'] as $i => $res) {
                    CareerPathResource::create([
                        'career_path_task_id' => $task->id,
                        'type'        => $res['type'],
                        'title'       => $res['title'],
                        'url'         => $res['url'],
                        'description' => $res['description'] ?? null,
                        'provider'    => $res['provider'] ?? null,
                        'is_free'     => $res['is_free'] ?? true,
                        'sort_order'  => $i,
                    ]);
                }
            }

            // Create keywords
            if (!empty($validated['keywords'])) {
                foreach ($validated['keywords'] as $i => $kw) {
                    CareerPathKeyword::create([
                        'career_path_task_id' => $task->id,
                        'keyword'    => $kw['keyword'],
                        'definition' => $kw['definition'] ?? null,
                        'importance' => $kw['importance'] ?? 'important',
                        'sort_order' => $i,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Failed to create task.'], 500);
            }
            return back()->with('error', 'Failed to create task. Please try again.')->withInput();
        }

        $this->clearCache();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Task created successfully!', 'task_id' => $task->id]);
        }

        return redirect()->route('career-path.show', $careerPath)
                         ->with('success', "Task \"{$task->title}\" created successfully!");
    }

    // ── Show Task Detail (subtask/sub-subtask content view) ──

    public function showTask(CareerPath $careerPath, CareerPathTask $task)
    {
        $this->authorizeAccess($careerPath);

        if ($task->career_path_id !== $careerPath->id) {
            abort(404);
        }

        $task->load(['resources', 'keywords', 'children.resources', 'children.keywords', 'parent']);

        return view('career-path.task-show', compact('careerPath', 'task'));
    }

    // ── Edit Task ──

    public function editTask(CareerPath $careerPath, CareerPathTask $task)
    {
        $this->authorizeAccess($careerPath);

        if ($task->career_path_id !== $careerPath->id) {
            abort(404);
        }

        $task->load(['resources', 'keywords']);

        return view('career-path.task-edit', compact('careerPath', 'task'));
    }

    // ── Update Task ──

    public function updateTask(Request $request, CareerPath $careerPath, CareerPathTask $task)
    {
        $this->authorizeAccess($careerPath);

        if ($task->career_path_id !== $careerPath->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string', 'max:5000'],
            'content'          => ['nullable', 'string', 'max:50000'],
            'priority'         => ['required', 'in:low,medium,high,urgent'],
            'status'           => ['required', 'in:not_started,in_progress,completed,skipped'],
            'estimated_hours'  => ['nullable', 'integer', 'min:1', 'max:1000'],
            'start_date'       => ['nullable', 'date'],
            'due_date'         => ['nullable', 'date'],
            // Multiple videos
            'videos'           => ['nullable', 'array', 'max:20'],
            'videos.*.title'   => ['nullable', 'string', 'max:255'],
            'videos.*.url'     => ['required_with:videos', 'url', 'max:500'],
            // Resources
            'resources'          => ['nullable', 'array', 'max:20'],
            'resources.*.id'     => ['nullable', 'integer'],
            'resources.*.type'   => ['required_with:resources', 'in:link,video,article,course,book,tool,documentation,other'],
            'resources.*.title'  => ['required_with:resources', 'string', 'max:255'],
            'resources.*.url'    => ['required_with:resources', 'url', 'max:500'],
            'resources.*.description' => ['nullable', 'string', 'max:500'],
            'resources.*.provider'    => ['nullable', 'string', 'max:100'],
            'resources.*.is_free'     => ['nullable', 'boolean'],
            // Keywords
            'keywords'               => ['nullable', 'array', 'max:30'],
            'keywords.*.id'          => ['nullable', 'integer'],
            'keywords.*.keyword'     => ['required_with:keywords', 'string', 'max:100'],
            'keywords.*.definition'  => ['nullable', 'string', 'max:500'],
            'keywords.*.importance'  => ['nullable', 'in:essential,important,good_to_know'],
        ]);

        // Extract videos before task update
        $videos = $validated['videos'] ?? [];
        unset($validated['videos']);
        // Set video_url to first video for backward compatibility
        $validated['video_url'] = !empty($videos) && !empty($videos[0]['url']) ? $videos[0]['url'] : null;
        $validated['duration_minutes'] = null;

        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        DB::beginTransaction();
        try {
            $task->update($validated);

            // Sync all resources: delete everything, recreate non-video resources + video resources
            $task->resources()->delete();
            $allResources = array_merge(
                array_values($validated['resources'] ?? []),
                array_map(fn($v, $i) => [
                    'type'       => 'video',
                    'title'      => $v['title'] ?: 'Video ' . ($i + 1),
                    'url'        => $v['url'],
                    'is_free'    => true,
                    'sort_order' => count($validated['resources'] ?? []) + $i,
                ], array_filter($videos, fn($v) => !empty($v['url'])), array_keys(array_filter($videos, fn($v) => !empty($v['url']))))
            );
            foreach ($allResources as $i => $res) {
                CareerPathResource::create([
                    'career_path_task_id' => $task->id,
                    'type'        => $res['type'],
                    'title'       => $res['title'],
                    'url'         => $res['url'],
                    'description' => $res['description'] ?? null,
                    'provider'    => $res['provider'] ?? null,
                    'is_free'     => $res['is_free'] ?? true,
                    'sort_order'  => $res['sort_order'] ?? $i,
                ]);
            }

            // Sync keywords
            $task->keywords()->delete();
            if (!empty($validated['keywords'])) {
                foreach ($validated['keywords'] as $i => $kw) {
                    CareerPathKeyword::create([
                        'career_path_task_id' => $task->id,
                        'keyword'    => $kw['keyword'],
                        'definition' => $kw['definition'] ?? null,
                        'importance' => $kw['importance'] ?? 'important',
                        'sort_order' => $i,
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update task. Please try again.')->withInput();
        }

        $this->clearCache();

        return redirect()->route('career-path.task.show', [$careerPath, $task])
                         ->with('success', 'Task updated successfully!');
    }

    // ── Delete Task ──

    public function destroyTask(CareerPath $careerPath, CareerPathTask $task)
    {
        $this->authorizeAccess($careerPath);

        if ($task->career_path_id !== $careerPath->id) {
            abort(404);
        }

        $task->delete();
        $this->clearCache();

        return redirect()->route('career-path.show', $careerPath)
                         ->with('success', 'Task deleted successfully!');
    }

    // ── API: Quick status update ──

    public function updateTaskStatus(Request $request, CareerPathTask $task)
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:not_started,in_progress,completed,skipped'],
        ]);

        $oldStatus = $task->status;

        if ($validated['status'] === 'completed') {
            $task->markAsCompleted();
        } elseif ($validated['status'] === 'in_progress') {
            $task->markAsInProgress();
        } else {
            $task->update($validated);
        }

        $this->clearCache();

        return response()->json([
            'success'    => true,
            'task'       => $task->fresh(),
            'old_status' => $oldStatus,
            'message'    => 'Status updated to ' . str_replace('_', ' ', $task->status),
        ]);
    }

    // ── Private helpers ──

    private function authorizeAccess(CareerPath $careerPath): void
    {
        if ($careerPath->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to access this career path.');
        }
    }

    private function clearCache(): void
    {
        $userId = Auth::id();
        Cache::forget("dashboard_stats_{$userId}");
        Cache::forget("recent_tasks_{$userId}");
        Cache::forget("career_paths_{$userId}");
    }
}
