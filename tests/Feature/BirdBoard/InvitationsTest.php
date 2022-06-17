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
        $this->post($project->path() . '/members', ['email' => $user->email])->assertRedirect($project->path());
        self::logIn($user);

        $this->post($project->path() . '/tasks', $task = Task::factory()->raw())->assertRedirect($project->path());

        $this->assertDatabaseHas('tasks', $task);
    }

    public function test_only_a_registered_user_can_be_invited()
    {
        $project = self::createProject();
        $user = User::factory()->make();

        $this->post($project->path() . '/members', ['email' => $user->email])
            ->assertSessionHasErrors(
                ['email' => "Email {$user->email} hasn't been registered in the system"],
                errorBag: 'invite'
            );

    }

    public function test_only_an_owner_can_invite_a_user()
    {
        $project = self::createProject();
        $user = self::logIn();
        $project->inviteMember($user);

        $this->post($project->path() . '/members', ['email' => $email = User::factory()->create()->email])
            ->assertStatus(403);

        self::logIn(User::whereKey($project->owner->id)->first());
        $this->post($project->path() . '/members', ['email' => $email])
            ->assertRedirect($project->path());
    }

    public function test_a_user_can_see_all_projects_they_have_been_invited_to()
    {
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

    public function test_only_an_owner_can_see_the_invitation_card()
    {
        $project = self::createProject();
        $this->get($project->path())->assertSee('Invite a Member');

        self::logIn();
        $this->get($project->path())->assertDontSee('Invite a Member');

    }




}
