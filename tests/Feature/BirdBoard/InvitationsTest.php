<?php

namespace Tests\Feature\BirdBoard;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_invited_user_can_add_a_task_to_a_project()
    {
        $project = self::createProject();
        $user = User::factory()->create();
        $this->post($project->path() . '/members', ['user_id' => $user->id])->assertStatus(200);
        self::logIn($user);

        $this->post($project->path() . '/tasks', $task = Task::factory()->raw())->assertRedirect($project->path());

        $this->assertDatabaseHas('tasks', $task);
    }

}
