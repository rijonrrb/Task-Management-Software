<?php

namespace App\Http\Controllers;

use App\Models\AiPrompt;
use App\Models\CareerPath;
use App\Models\CareerPathKeyword;
use App\Models\CareerPathResource;
use App\Models\CareerPathTask;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutomatedTaskController extends Controller
{
    /**
     * Landing page — show available AI services.
     */
    public function index()
    {
        return view('automated-task.index');
    }

    /**
     * Career path generation form.
     */
    public function careerPathForm()
    {
        return view('automated-task.career-path');
    }

    /**
     * Generate a career path using AI.
     */
    public function generateCareerPath(Request $request, AiService $aiService)
    {
        $validated = $request->validate([
            'career_goal'   => ['required', 'string', 'max:500'],
            'current_level' => ['required', 'in:beginner,intermediate,advanced'],
            'target_level'  => ['required', 'in:beginner,intermediate,advanced'],
            'include_quiz'  => ['nullable', 'boolean'],
        ]);

        // ── Validate career goal: block illegal / illogical careers ──
        $blockedPatterns = [
            // Illegal activities
            '/\b(hack|crack|steal|fraud|scam|drug|trafficking|counterfeit|launder|pirat|exploit|phish|ransomware|malware|darknet|dark\s*web|illegal|crime|criminal)\b/i',
            // Violent / harmful
            '/\b(hitman|assassin|weapon|bomb|terror|mercenary|human\s*trafficking)\b/i',
            // Illogical / nonsensical
            '/\b(unicorn\s*trainer|dragon\s*tamer|time\s*traveler|wizard|sorcerer|vampire|zombie|alien\s*hunter|flat\s*earth)\b/i',
        ];

        foreach ($blockedPatterns as $pattern) {
            if (preg_match($pattern, $validated['career_goal'])) {
                return back()->withInput()->with('error', 'This career goal is not valid. Please enter a legitimate, real-world career path.');
            }
        }

        // ── Get AI prompt from database ──
        $prompt = AiPrompt::getBySlug('career-path-generator');

        if (!$prompt) {
            return back()->withInput()->with('error', 'AI prompt configuration not found. Please contact the administrator.');
        }

        // ── Build variables for the template ──
        $variables = [
            'career_goal'   => $validated['career_goal'],
            'current_level' => $validated['current_level'],
            'target_level'  => $validated['target_level'],
            'include_quiz'  => !empty($validated['include_quiz']) ? 'Include 2-3 quiz/practice questions per subtask for self-assessment.' : '',
        ];

        // ── Call AI ──
        $result = $aiService->generateFromPrompt($prompt, $variables);

        if (!$result['success']) {
            return back()->withInput()->with('error', $result['error']);
        }

        // ── Parse the AI response ──
        $data = $aiService->parseJson($result['content']);

        if (!$data) {
            return back()->withInput()->with('error', 'Failed to parse the AI response. Please try again.');
        }

        // ── Validate the essential structure ──
        if (empty($data['title']) || empty($data['tasks'])) {
            return back()->withInput()->with('error', 'AI returned an incomplete career path. Please try again.');
        }

        // ── Create the career path and all nested data ──
        DB::beginTransaction();
        try {
            $careerPath = CareerPath::create([
                'user_id'         => Auth::id(),
                'title'           => $data['title'],
                'description'     => $data['description'] ?? null,
                'target_role'     => $data['target_role'] ?? $validated['career_goal'],
                'current_level'   => $validated['current_level'],
                'target_level'    => $validated['target_level'],
                'source'          => 'ai',
                'status'          => 'active',
                'estimated_weeks' => $data['estimated_weeks'] ?? null,
                'start_date'      => now(),
                'tags'            => $data['tags'] ?? [],
                'metadata'        => [
                    'ai_model'     => $prompt->model,
                    'career_goal'  => $validated['career_goal'],
                    'include_quiz' => !empty($validated['include_quiz']),
                ],
            ]);

            // ── Create tasks (3-layer hierarchy) ──
            $this->createTasksFromAi($careerPath, $data['tasks']);

            DB::commit();

            return redirect()->route('career-path.show', $careerPath)
                             ->with('success', '🎉 AI-generated career path created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create AI career path', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Failed to save the career path. Please try again.');
        }
    }

    /**
     * Recursively create tasks from AI-generated data.
     */
    protected function createTasksFromAi(CareerPath $careerPath, array $tasks, ?int $parentId = null, int $depth = 0): void
    {
        foreach ($tasks as $index => $taskData) {
            $task = CareerPathTask::create([
                'career_path_id'   => $careerPath->id,
                'user_id'          => Auth::id(),
                'parent_id'        => $parentId,
                'title'            => $taskData['title'],
                'description'      => $taskData['description'] ?? null,
                'content'          => $taskData['content'] ?? null,
                'depth'            => $depth,
                'sort_order'       => $index,
                'priority'         => $taskData['priority'] ?? 'medium',
                'status'           => 'not_started',
                'estimated_hours'  => $taskData['estimated_hours'] ?? null,
                'video_url'        => $taskData['videos'][0]['url'] ?? null,
                'video_type'       => isset($taskData['videos'][0]) ? $this->detectVideoType($taskData['videos'][0]['url'] ?? '') : null,
                'source'           => 'ai',
            ]);

            // Create resources (articles, docs, tools, etc.)
            if (!empty($taskData['resources'])) {
                foreach ($taskData['resources'] as $ri => $res) {
                    CareerPathResource::create([
                        'career_path_task_id' => $task->id,
                        'type'        => $res['type'] ?? 'link',
                        'title'       => $res['title'],
                        'url'         => $res['url'],
                        'description' => $res['description'] ?? null,
                        'provider'    => $res['provider'] ?? null,
                        'is_free'     => $res['is_free'] ?? true,
                        'sort_order'  => $ri,
                    ]);
                }
            }

            // Create video resources
            if (!empty($taskData['videos'])) {
                foreach ($taskData['videos'] as $vi => $video) {
                    CareerPathResource::create([
                        'career_path_task_id' => $task->id,
                        'type'        => 'video',
                        'title'       => $video['title'] ?? 'Video Resource',
                        'url'         => $video['url'],
                        'description' => $video['description'] ?? null,
                        'provider'    => $this->detectVideoProvider($video['url'] ?? ''),
                        'is_free'     => true,
                        'sort_order'  => 100 + $vi,
                    ]);
                }
            }

            // Create keywords
            if (!empty($taskData['keywords'])) {
                foreach ($taskData['keywords'] as $ki => $kw) {
                    CareerPathKeyword::create([
                        'career_path_task_id' => $task->id,
                        'keyword'    => $kw['keyword'],
                        'definition' => $kw['definition'] ?? null,
                        'importance' => $kw['importance'] ?? 'important',
                        'sort_order' => $ki,
                    ]);
                }
            }

            // Recurse for children (subtasks / sub-subtasks)
            if (!empty($taskData['children']) && $depth < 2) {
                $this->createTasksFromAi($careerPath, $taskData['children'], $task->id, $depth + 1);
            }
        }
    }

    /**
     * Detect video type from URL.
     */
    protected function detectVideoType(string $url): ?string
    {
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return 'youtube';
        }
        if (str_contains($url, 'vimeo.com')) {
            return 'vimeo';
        }
        return 'other';
    }

    /**
     * Detect video provider from URL.
     */
    protected function detectVideoProvider(string $url): ?string
    {
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return 'YouTube';
        }
        if (str_contains($url, 'vimeo.com')) {
            return 'Vimeo';
        }
        if (str_contains($url, 'udemy.com')) {
            return 'Udemy';
        }
        if (str_contains($url, 'coursera.org')) {
            return 'Coursera';
        }
        return null;
    }
}
