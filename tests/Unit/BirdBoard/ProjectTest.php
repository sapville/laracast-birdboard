<?php

namespace Tests\Unit\BirdBoard;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_project_has_a_path()
    {
        $project = Project::factory()->make();
        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    public function test_a_project_assigned_to_a_user()
    {
        $user = User::factory()->create();
        $project = Project::factory()->for($user, 'owner')->create();

        $this->assertTrue($project->owner->is($user));
    }

    public function test_a_project_can_add_a_task()
    {
        $project = Project::factory()->create();
        $task = $project->addTask('Task Body');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
        $this->assertTrue($project->is($task->project));
    }

}
