<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_project_requires_an_owner()
    {
        $this->post('/projects', Project::factory()->create(['owner_id' => null])->getAttributes())
            ->assertSessionHasErrors(['owner_id']);
    }

    public function test_a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();

        $attributes = Project::factory()->make()->getAttributes();

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);

    }

    public function test_a_user_can_see_a_project()
    {
        $this->withoutExceptionHandling();

        $project = Project::factory()->create();
        $this->post('/projects', $project->getAttributes());

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_a_project_title_is_validated()
    {
        $attributes = Project::factory()->make(['title' => ''])->getAttributes();

        $this->post('/projects', $attributes)->assertSessionHasErrors(['title']);
    }

    public function test_a_project_description_is_validated()
    {
        $attributes = Project::factory()->make(['description' => ''])->getAttributes();

        $this->post('/projects', $attributes)->assertSessionHasErrors(['description']);
    }
}
