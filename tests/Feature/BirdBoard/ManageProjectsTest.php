<?php

namespace Tests\Feature\BirdBoard;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_manage_a_project()
    {
        $project = Project::factory()->create();
        $this->post('/projects', $project->getAttributes())->assertRedirect('/login');
        $this->get(Project::factory()->create()->path())->assertRedirect('/login');
        $this->get('/projects')->assertRedirect('/login');
        $this->get('/projects/create')->assertRedirect('/login');
        $this->get($project->path() . '/edit')->assertRedirect('/login');
        $this->patch($project->path(), Project::factory()->raw())->assertRedirect('/login');
    }

    public function test_a_user_can_create_a_project()
    {
        TestCase::logIn();
        $attributes = Project::factory()->raw([
            'description' => Str::uuid(),
            'owner_id' => Auth::id(),
        ]);

        $this->get('/projects/create')->assertStatus(200);
        $response = $this->post('/projects', $attributes);

        $this->assertDatabaseHas('projects', $attributes);
        $project = Project::where('description', $attributes['description'])->first();
        $response->assertRedirect($project->path());
        $this->get($project->path())->assertSee($attributes);

    }

    public function test_a_user_can_update_a_project()
    {
        $old_project = TestCase::createProject();
        $new_project_attributes = Project::factory()->raw(['owner_id' => Auth::id()]);

        $this->get($old_project->path(). '/edit')->assertStatus(200);

        $this->patch($old_project->path(), $new_project_attributes)->assertRedirect($old_project->path());
        $this->assertDatabaseHas('projects', $new_project_attributes);
        $this->get($old_project->path())->assertSee(Arr::except($new_project_attributes, ['updated_at', 'created_at']));
    }

    public function test_an_owner_can_update_only_their_projects()
    {
        $old_project = TestCase::createProject();
        $new_project_attributes = TestCase::createProject()->getAttributes();

        $this->patch($old_project->path(), $new_project_attributes)->assertStatus(403);

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
        $project = static::update_project(attributes: ['title' => '']);
        $this->patch($project->path(), $project->getAttributes())->assertSessionHasErrors('title');

    }

    public function test_a_project_description_is_validated()
    {
        TestCase::logIn();
        $attributes = Project::factory()->make(['description' => ''])->getAttributes();

        $this->post('/projects', $attributes)->assertSessionHasErrors(['description']);
        $project = static::update_project(attributes: ['description' => '']);
        $this->patch($project->path(), $project->getAttributes())->assertSessionHasErrors('description');
    }
}
