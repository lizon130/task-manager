<?php
// database/factories/TaskFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        $dueDate = $this->faker->optional(0.7)->dateTimeBetween('now', '+30 days');
        
        return [
            'project_id' => \App\Models\Project::factory(),
            'user_id' => \App\Models\User::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional(0.8)->paragraph(),
            'completed' => $this->faker->boolean(20),
            'due_date' => $dueDate,
            'priority' => $this->faker->randomElement([1, 1, 1, 2, 2, 3]), // More low priority tasks
        ];
    }

    // State methods for specific scenarios
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed' => true,
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 3,
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
            'completed' => false,
        ]);
    }
}