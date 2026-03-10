<?php

namespace Database\Seeders;

use App\Models\CareerPath;
use App\Models\CareerPathTask;
use App\Models\CareerPathResource;
use App\Models\CareerPathKeyword;
use App\Models\User;
use Illuminate\Database\Seeder;

class CareerPathSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            return;
        }

        // ══════════════════════════════════════════
        // Career Path 1: Full-Stack Developer
        // ══════════════════════════════════════════
        $path = CareerPath::create([
            'user_id' => $user->id,
            'title' => 'Full-Stack Web Developer',
            'slug' => 'full-stack-web-developer',
            'description' => 'Complete roadmap from beginner to professional full-stack developer covering frontend, backend, databases, and deployment.',
            'target_role' => 'Full-Stack Developer',
            'current_level' => 'beginner',
            'target_level' => 'advanced',
            'source' => 'manual',
            'status' => 'active',
            'estimated_weeks' => 24,
            'start_date' => now(),
            'target_date' => now()->addWeeks(24),
            'tags' => ['php', 'laravel', 'vue', 'javascript', 'mysql', 'tailwind'],
        ]);

        // ── Main Task 1: Frontend Fundamentals ──
        $mt1 = CareerPathTask::create([
            'career_path_id' => $path->id,
            'user_id' => $user->id,
            'parent_id' => null,
            'title' => 'Frontend Fundamentals',
            'description' => 'Master HTML5, CSS3, and modern JavaScript to build beautiful, responsive web interfaces.',
            'depth' => 0,
            'sort_order' => 1,
            'priority' => 'high',
            'status' => 'in_progress',
            'estimated_hours' => 40,
            'source' => 'manual',
        ]);

        // Subtask 1.1
        $st1 = CareerPathTask::create([
            'career_path_id' => $path->id,
            'user_id' => $user->id,
            'parent_id' => $mt1->id,
            'title' => 'HTML5 Semantic Elements',
            'description' => 'Learn semantic HTML5 tags for better SEO and accessibility.',
            'content' => "HTML5 introduced semantic elements that clearly describe their meaning to both the browser and developer.\n\nKey elements to master:\n- <header>, <footer>, <nav>, <main>\n- <article>, <section>, <aside>\n- <figure>, <figcaption>\n- <details>, <summary>\n- <time>, <mark>, <progress>",
            'depth' => 1,
            'sort_order' => 1,
            'priority' => 'high',
            'status' => 'completed',
            'estimated_hours' => 4,
            'video_url' => 'https://www.youtube.com/watch?v=kUMe1FH4CHE',
            'video_type' => 'youtube',
            'duration_minutes' => 15,
            'completed_at' => now()->subDays(5),
            'source' => 'manual',
        ]);

        CareerPathResource::insert([
            ['career_path_task_id' => $st1->id, 'type' => 'documentation', 'title' => 'MDN HTML Elements Reference', 'url' => 'https://developer.mozilla.org/en-US/docs/Web/HTML/Element', 'provider' => 'MDN', 'is_free' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['career_path_task_id' => $st1->id, 'type' => 'article', 'title' => 'HTML5 Semantic Elements Guide', 'url' => 'https://www.w3schools.com/html/html5_semantic_elements.asp', 'provider' => 'W3Schools', 'is_free' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['career_path_task_id' => $st1->id, 'type' => 'video', 'title' => 'HTML Crash Course For Beginners', 'url' => 'https://www.youtube.com/watch?v=UB1O30fR-EE', 'provider' => 'YouTube', 'is_free' => true, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        CareerPathKeyword::insert([
            ['career_path_task_id' => $st1->id, 'keyword' => 'Semantic HTML', 'definition' => 'HTML tags that convey meaning about the content they contain.', 'importance' => 'essential', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['career_path_task_id' => $st1->id, 'keyword' => 'Accessibility', 'definition' => 'Making web content usable by people with disabilities.', 'importance' => 'essential', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['career_path_task_id' => $st1->id, 'keyword' => 'SEO', 'definition' => 'Search Engine Optimization for better page ranking.', 'importance' => 'important', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Sub-subtask 1.1.1
        CareerPathTask::create([
            'career_path_id' => $path->id,
            'user_id' => $user->id,
            'parent_id' => $st1->id,
            'title' => 'Build a Semantic Blog Layout',
            'description' => 'Create a complete blog page using only semantic HTML5 elements.',
            'depth' => 2,
            'sort_order' => 1,
            'priority' => 'medium',
            'status' => 'completed',
            'estimated_hours' => 2,
            'completed_at' => now()->subDays(4),
            'source' => 'manual',
        ]);

        // Subtask 1.2
        $st2 = CareerPathTask::create([
            'career_path_id' => $path->id,
            'user_id' => $user->id,
            'parent_id' => $mt1->id,
            'title' => 'CSS3 Flexbox & Grid',
            'description' => 'Master modern CSS layout systems for responsive design.',
            'content' => "Modern CSS layout is built around two powerful systems:\n\n1. Flexbox — for one-dimensional layouts (rows OR columns)\n2. CSS Grid — for two-dimensional layouts (rows AND columns)\n\nBoth are essential for modern responsive web design.",
            'depth' => 1,
            'sort_order' => 2,
            'priority' => 'high',
            'status' => 'in_progress',
            'estimated_hours' => 8,
            'video_url' => 'https://www.youtube.com/watch?v=JJSoEo8JSnc',
            'video_type' => 'youtube',
            'duration_minutes' => 23,
            'source' => 'manual',
        ]);

        CareerPathResource::insert([
            ['career_path_task_id' => $st2->id, 'type' => 'tool', 'title' => 'Flexbox Froggy (Interactive)', 'url' => 'https://flexboxfroggy.com/', 'provider' => 'Flexbox Froggy', 'is_free' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['career_path_task_id' => $st2->id, 'type' => 'tool', 'title' => 'CSS Grid Garden', 'url' => 'https://cssgridgarden.com/', 'provider' => 'Grid Garden', 'is_free' => true, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['career_path_task_id' => $st2->id, 'type' => 'documentation', 'title' => 'CSS Tricks: Complete Guide to Flexbox', 'url' => 'https://css-tricks.com/snippets/css/a-guide-to-flexbox/', 'provider' => 'CSS-Tricks', 'is_free' => true, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        CareerPathKeyword::insert([
            ['career_path_task_id' => $st2->id, 'keyword' => 'Flexbox', 'definition' => 'CSS layout model for distributing space in a single axis.', 'importance' => 'essential', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['career_path_task_id' => $st2->id, 'keyword' => 'CSS Grid', 'definition' => 'Two-dimensional grid-based layout system for CSS.', 'importance' => 'essential', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['career_path_task_id' => $st2->id, 'keyword' => 'Responsive Design', 'definition' => 'Designing websites that work on all screen sizes.', 'importance' => 'important', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Subtask 1.3
        $st3 = CareerPathTask::create([
            'career_path_id' => $path->id,
            'user_id' => $user->id,
            'parent_id' => $mt1->id,
            'title' => 'Tailwind CSS Framework',
            'description' => 'Learn utility-first CSS with Tailwind for rapid UI development.',
            'depth' => 1,
            'sort_order' => 3,
            'priority' => 'medium',
            'status' => 'not_started',
            'estimated_hours' => 6,
            'source' => 'manual',
        ]);

        // ── Main Task 2: JavaScript & Vue.js ──
        $mt2 = CareerPathTask::create([
            'career_path_id' => $path->id,
            'user_id' => $user->id,
            'parent_id' => null,
            'title' => 'JavaScript & Vue.js',
            'description' => 'Master modern JavaScript ES6+ and the Vue.js 3 framework for reactive interfaces.',
            'depth' => 0,
            'sort_order' => 2,
            'priority' => 'high',
            'status' => 'not_started',
            'estimated_hours' => 60,
            'source' => 'manual',
        ]);

        CareerPathTask::create([
            'career_path_id' => $path->id,
            'user_id' => $user->id,
            'parent_id' => $mt2->id,
            'title' => 'ES6+ Modern JavaScript',
            'description' => 'Arrow functions, destructuring, modules, promises, async/await.',
            'depth' => 1,
            'sort_order' => 1,
            'priority' => 'high',
            'status' => 'not_started',
            'estimated_hours' => 15,
            'source' => 'manual',
        ]);

        CareerPathTask::create([
            'career_path_id' => $path->id,
            'user_id' => $user->id,
            'parent_id' => $mt2->id,
            'title' => 'Vue.js 3 Composition API',
            'description' => 'Learn reactive state, composables, and component architecture.',
            'depth' => 1,
            'sort_order' => 2,
            'priority' => 'high',
            'status' => 'not_started',
            'estimated_hours' => 20,
            'source' => 'manual',
        ]);

        // ── Main Task 3: Backend with Laravel ──
        $mt3 = CareerPathTask::create([
            'career_path_id' => $path->id,
            'user_id' => $user->id,
            'parent_id' => null,
            'title' => 'Backend with Laravel',
            'description' => 'Build robust server-side applications with PHP and the Laravel framework.',
            'depth' => 0,
            'sort_order' => 3,
            'priority' => 'high',
            'status' => 'not_started',
            'estimated_hours' => 50,
            'source' => 'manual',
        ]);

        CareerPathTask::create([
            'career_path_id' => $path->id,
            'user_id' => $user->id,
            'parent_id' => $mt3->id,
            'title' => 'Laravel Routing & Controllers',
            'description' => 'RESTful routes, resource controllers, middleware.',
            'depth' => 1,
            'sort_order' => 1,
            'priority' => 'high',
            'status' => 'not_started',
            'estimated_hours' => 10,
            'source' => 'manual',
        ]);

        CareerPathTask::create([
            'career_path_id' => $path->id,
            'user_id' => $user->id,
            'parent_id' => $mt3->id,
            'title' => 'Eloquent ORM & Relationships',
            'description' => 'Models, migrations, relationships, query scopes, accessors.',
            'depth' => 1,
            'sort_order' => 2,
            'priority' => 'high',
            'status' => 'not_started',
            'estimated_hours' => 12,
            'source' => 'manual',
        ]);

        // ══════════════════════════════════════════
        // Career Path 2: Data Science (shorter)
        // ══════════════════════════════════════════
        $path2 = CareerPath::create([
            'user_id' => $user->id,
            'title' => 'Data Science & Machine Learning',
            'slug' => 'data-science-machine-learning',
            'description' => 'Learn data analysis, visualization, and ML fundamentals with Python.',
            'target_role' => 'Data Scientist',
            'current_level' => 'beginner',
            'target_level' => 'intermediate',
            'source' => 'manual',
            'status' => 'active',
            'estimated_weeks' => 16,
            'start_date' => now(),
            'target_date' => now()->addWeeks(16),
            'tags' => ['python', 'pandas', 'numpy', 'scikit-learn', 'matplotlib'],
        ]);

        $dsMt1 = CareerPathTask::create([
            'career_path_id' => $path2->id,
            'user_id' => $user->id,
            'parent_id' => null,
            'title' => 'Python for Data Science',
            'description' => 'Foundation Python skills for data analysis and manipulation.',
            'depth' => 0,
            'sort_order' => 1,
            'priority' => 'high',
            'status' => 'not_started',
            'estimated_hours' => 30,
            'source' => 'manual',
        ]);

        CareerPathTask::create([
            'career_path_id' => $path2->id,
            'user_id' => $user->id,
            'parent_id' => $dsMt1->id,
            'title' => 'NumPy & Pandas Basics',
            'description' => 'Data structures, array operations, DataFrames, series.',
            'depth' => 1,
            'sort_order' => 1,
            'priority' => 'high',
            'status' => 'not_started',
            'estimated_hours' => 10,
            'source' => 'manual',
        ]);
    }
}
