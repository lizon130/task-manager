<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        \App\Models\User::factory(10)->create()->each(function ($user) {
            \App\Models\Project::factory(3)->create(['user_id' => $user->id])
                ->each(function ($project) use ($user) {
                    \App\Models\Task::factory(5)->create([
                        'project_id' => $project->id,
                        'user_id' => $user->id,
                    ]);
                });
        });
    }
}