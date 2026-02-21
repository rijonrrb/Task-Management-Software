<?php

namespace Database\Seeders;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  SEEDER: DatabaseSeeder                                      ║
 * ║  Purpose: Populate database with sample data for development ║
 * ║  Learning: Seeders, factories, relationships in seeding      ║
 * ╚══════════════════════════════════════════════════════════════╝
 *
 * Run with: php artisan migrate:fresh --seed
 * This drops all tables, re-creates them, and runs this seeder.
 */

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Step 1: Create demo user ────────────────────────────
        // You can log in with this account for testing
        $demoUser = User::factory()->create([
            'name'     => 'Rijon Demo',
            'email'    => 'demo@taskflow.com',
            'password' => 'password', // Hashed automatically by model cast
        ]);

        // Create a second user for testing multi-user scenarios
        $secondUser = User::factory()->create([
            'name'     => 'Jane Smith',
            'email'    => 'jane@taskflow.com',
            'password' => 'password',
        ]);

        // ── Step 2: Create categories ───────────────────────────
        $categories = collect([
            ['name' => 'Work',     'slug' => 'work',     'color' => '#3B82F6', 'description' => 'Work-related tasks and projects'],
            ['name' => 'Personal', 'slug' => 'personal', 'color' => '#10B981', 'description' => 'Personal errands and goals'],
            ['name' => 'Shopping', 'slug' => 'shopping', 'color' => '#F59E0B', 'description' => 'Shopping lists and purchases'],
            ['name' => 'Health',   'slug' => 'health',   'color' => '#EF4444', 'description' => 'Health and fitness tasks'],
            ['name' => 'Learning', 'slug' => 'learning', 'color' => '#8B5CF6', 'description' => 'Study and learning goals'],
            ['name' => 'Finance',  'slug' => 'finance',  'color' => '#06B6D4', 'description' => 'Financial tasks and reminders'],
        ])->map(fn($cat) => Category::create($cat));

        // ── Step 3: Create sample tasks for demo user ───────────
        $sampleTasks = [
            // Pending tasks
            ['title' => 'Set up Redis caching for the API', 'priority' => 'high', 'status' => 'pending', 'category_id' => $categories[0]->id, 'due_date' => now()->addDays(3)],
            ['title' => 'Buy groceries for the week', 'priority' => 'medium', 'status' => 'pending', 'category_id' => $categories[2]->id, 'due_date' => now()->addDay()],
            ['title' => 'Review pull request #42', 'priority' => 'urgent', 'status' => 'pending', 'category_id' => $categories[0]->id, 'due_date' => now()],
            ['title' => 'Schedule dentist appointment', 'priority' => 'low', 'status' => 'pending', 'category_id' => $categories[3]->id, 'due_date' => now()->addWeek()],
            ['title' => 'Learn Vue 3 Composition API', 'priority' => 'medium', 'status' => 'pending', 'category_id' => $categories[4]->id, 'due_date' => now()->addDays(5)],

            // In Progress tasks
            ['title' => 'Build the task management dashboard', 'priority' => 'high', 'status' => 'in_progress', 'category_id' => $categories[0]->id, 'due_date' => now()->addDays(2)],
            ['title' => 'Read "Clean Code" Chapter 5', 'priority' => 'medium', 'status' => 'in_progress', 'category_id' => $categories[4]->id, 'due_date' => now()->addDays(4)],
            ['title' => 'Prepare monthly budget report', 'priority' => 'high', 'status' => 'in_progress', 'category_id' => $categories[5]->id, 'due_date' => now()->addDays(2)],

            // Completed tasks
            ['title' => 'Set up Laravel 12 project', 'priority' => 'high', 'status' => 'completed', 'category_id' => $categories[0]->id, 'completed_at' => now()->subDay()],
            ['title' => 'Configure Tailwind CSS', 'priority' => 'medium', 'status' => 'completed', 'category_id' => $categories[0]->id, 'completed_at' => now()->subDay()],
            ['title' => 'Morning jog — 5km', 'priority' => 'low', 'status' => 'completed', 'category_id' => $categories[3]->id, 'completed_at' => now()->subHours(6)],

            // Overdue tasks (for testing overdue detection)
            ['title' => 'Submit tax documents', 'priority' => 'urgent', 'status' => 'pending', 'category_id' => $categories[5]->id, 'due_date' => now()->subDays(2)],
            ['title' => 'Renew gym membership', 'priority' => 'medium', 'status' => 'pending', 'category_id' => $categories[3]->id, 'due_date' => now()->subDay()],
        ];

        foreach ($sampleTasks as $task) {
            $demoUser->tasks()->create($task);
        }

        // ── Step 4: Create some tasks for the second user ────────
        Task::factory()->count(5)->create([
            'user_id' => $secondUser->id,
            'category_id' => $categories->random()->id,
        ]);

        // ── Summary ─────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('📧 Demo Login Credentials:');
        $this->command->info('   Email:    demo@taskflow.com');
        $this->command->info('   Password: password');
        $this->command->info('');
    }
}
