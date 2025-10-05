<?php
// tests/Feature/TaskTest.php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $project;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->project = Project::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_user_can_create_task(): void
    {
        $this->actingAs($this->user);

        $response = $this->post('/tasks', [
            'project_id' => $this->project->id,
            'title' => 'Test Task',
            'description' => 'Test Description',
            'priority' => 2,
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'user_id' => $this->user->id,
            'project_id' => $this->project->id,
        ]);
    }

    public function test_user_can_view_their_tasks(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'project_id' => $this->project->id,
        ]);

        $this->actingAs($this->user)
            ->get('/tasks')
            ->assertStatus(200)
            ->assertSee($task->title);
    }

    public function test_user_cannot_view_other_users_tasks(): void
    {
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->get(route('tasks.show', $task))
            ->assertForbidden();
    }

    public function test_user_can_update_their_task(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'project_id' => $this->project->id,
        ]);

        $this->actingAs($this->user)
            ->put(route('tasks.update', $task), [
                'project_id' => $this->project->id,
                'title' => 'Updated Task',
                'description' => 'Updated Description',
                'priority' => 3,
                'completed' => true,
            ])
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
            'completed' => true,
        ]);
    }

    public function test_user_can_delete_their_task(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'project_id' => $this->project->id,
        ]);

        $this->actingAs($this->user)
            ->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}