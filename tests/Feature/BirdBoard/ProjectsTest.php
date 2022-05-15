<?php

namespace Tests\Feature\BirdBoard;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_post_a_project()
    {
        $this->post('/projects', Project::factory()->raw())->assertRedirect('/login');
    }

    public function test_a_guest_cannot_view_a_project()
    {
        $this->get(Project::factory()->create()->path())->assertRedirect('/login');
    }

    public function test_a_guest_cannot_view_projects()
    {
        $this->get('/projects')->assertRedirect('/login');
    }

    public function test_a_user_can_create_a_project()
    {
        Auth::login(User::factory()->create());
        $this->withoutExceptionHandling();
        $attributes = Project::factory()->make(['owner_id' => Auth::user()->id])->getAttributes();

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);
        $this->get('/projects')->assertSee($attributes['title']);

    }

    public function test_a_user_can_see_a_project()
    {
        Auth::login(User::factory()->create());
        $this->withoutExceptionHandling();
        $project = Project::factory()->create(['owner_id' => Auth::id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_an_owner_can_view_only_their_projects()
    {
        Auth::login(User::factory()->create());
        $project = Project::factory()->create(['owner_id' => User::factory()->create()]);

        $this->get($project->path())->assertStatus(403);
    }

    public function test_a_project_title_is_validated()
    {
        Auth::login(User::factory()->create());
        $attributes = Project::factory()->make(['title' => ''])->getAttributes();

        $this->post('/projects', $attributes)->assertSessionHasErrors(['title']);
    }

    public function test_a_project_description_is_validated()
    {
        Auth::login(User::factory()->create());
        $attributes = Project::factory()->make(['description' => ''])->getAttributes();

        $this->post('/projects', $attributes)->assertSessionHasErrors(['description']);
    }
}
