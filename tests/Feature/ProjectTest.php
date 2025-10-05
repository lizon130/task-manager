<?php
// tests/Feature/ProjectTest.php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_create_project(): void
    {
        $this->actingAs($this->user);

        $response = $this->post('/projects', [
            'name' => 'Test Project',
            'description' => 'Test Description',
        ]);

        $response->assertRedirect(route('projects.index'));
        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_view_their_projects(): void
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->get('/projects')
            ->assertStatus(200)
            ->assertSee($project->name);
    }

    public function test_user_cannot_view_other_users_projects(): void
    {
        $otherUser = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->get(route('projects.show', $project))
            ->assertForbidden();
    }

    public function test_user_can_update_their_project(): void
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->put(route('projects.update', $project), [
                'name' => 'Updated Project',
                'description' => 'Updated Description',
            ])
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated Project',
        ]);
    }

    public function test_user_can_delete_their_project(): void
    {
        $project = Project::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->delete(route('projects.destroy', $project))
            ->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}