<?php

namespace Database\Factories;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  FACTORY: TaskFactory                                        ║
 * ║  Purpose: Generate fake task data for testing/seeding        ║
 * ║  Learning: Faker library, factory states, relationships      ║
 * ╚══════════════════════════════════════════════════════════════╝
 */

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     * Faker generates realistic-looking fake data.
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['pending', 'in_progress', 'completed', 'cancelled']);

        return [
            'user_id'     => User::factory(),
            'category_id' => null,
            'title'       => fake()->sentence(rand(3, 8)),
            'description' => fake()->optional(0.7)->paragraph(rand(1, 3)),
            'priority'    => fake()->randomElement(['low', 'medium', 'medium', 'high', 'urgent']),
            'status'      => $status,
            'due_date'    => fake()->optional(0.8)->dateTimeBetween('-1 week', '+2 weeks'),
            'completed_at' => $status === 'completed' ? fake()->dateTimeBetween('-3 days', 'now') : null,
        ];
    }

    // ──────────────────────────────────────────────
    // FACTORY STATES (chainable modifiers)
    // Usage: Task::factory()->urgent()->create()
    // ──────────────────────────────────────────────

    /**
     * State: Make the task urgent.
     */
    public function urgent(): static
    {
        return $this->state(fn() => [
            'priority' => 'urgent',
            'status' => 'pending',
        ]);
    }

    /**
     * State: Make the task completed.
     */
    public function completed(): static
    {
        return $this->state(fn() => [
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * State: Make the task overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn() => [
            'status' => 'pending',
            'due_date' => fake()->dateTimeBetween('-2 weeks', '-1 day'),
        ]);
    }
}
