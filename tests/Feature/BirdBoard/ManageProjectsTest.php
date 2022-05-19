<?php

namespace Tests\Feature\BirdBoard;

use App\Models\Project;
use Faker\UniqueGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_manage_a_project()
    {
        $this->post('/projects', Project::factory()->raw())->assertRedirect('/login');
        $this->get(Project::factory()->create()->path())->assertRedirect('/login');
        $this->get('/projects')->assertRedirect('/login');
        $this->get('/projects/create')->assertRedirect('/login');
    }

    public function test_a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        TestCase::logIn();

        $attributes =Project::factory()->raw([
            'owner_id' => Auth::id(),
            'description' => Str::uuid(),
        ]);

        $this->get('/projects/create')->assertStatus(200);
        $response = $this->post('/projects', $attributes);

        $this->assertDatabaseHas('projects', $attributes);
        $response->assertRedirect(Project::where($attributes)->first()->path());
        $this->get('/projects')->assertSee($attributes['title']);

    }

    public function test_a_user_can_see_a_project()
    {
        $this->withoutExceptionHandling();
        $project = TestCase::createProject();

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_an_owner_can_view_only_their_projects()
    {
        TestCase::logIn();
        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }

    public function test_a_project_title_is_validated()
    {
        TestCase::logIn();
        $attributes = Project::factory()->make(['title' => ''])->getAttributes();

        $this->post('/projects', $attributes)->assertSessionHasErrors(['title']);
    }

    public function test_a_project_description_is_validated()
    {
        TestCase::logIn();
        $attributes = Project::factory()->make(['description' => ''])->getAttributes();

        $this->post('/projects', $attributes)->assertSessionHasErrors(['description']);
    }
}
