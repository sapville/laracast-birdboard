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

    public function test_a_user_can_see_all_projects_they_have_been_invited_to()
    {
        $this->withoutExceptionHandling();

        $project = self::createProject();
        $john_project = self::createProject(user: $john = User::factory()->create());
        $jack_project = self::createProject(user: $jack = User::factory()->create());

        $this->get(route('projects.index'))->assertStatus(200)
            ->assertSee($jack_project->title)
            ->assertDontSee($project->title);
        self::logIn($john);
        $this->get(route('projects.index'))->assertStatus(200)
            ->assertSee($john_project->title)
            ->assertDontSee($project->title);

        $project->inviteMember($john);
        $john->refresh();

        $this->get(route('projects.index'))->assertStatus(200)
            ->assertSee($john_project->title)
            ->assertSee($project->title);
        self::logIn($jack);
        $this->get(route('projects.index'))->assertStatus(200)
            ->assertSee($jack_project->title)
            ->assertDontSee($project->title);

        $project->inviteMember($jack);
        $jack->refresh();

        $this->get(route('projects.index'))
            ->assertSee($jack_project->title)
            ->assertSee($project->title);
        self::logIn($john);
        $this->get(route('projects.index'))
            ->assertSee($john_project->title)
            ->assertSee($project->title);
    }


}
