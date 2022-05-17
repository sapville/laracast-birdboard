<?php

namespace Tests\Feature\BirdBoard;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_assign_a_task()
    {
        $project = TestCase::createProject(null, false);
        $this->post($project->path() . '/tasks', Task::factory()->raw())->assertRedirect('/login');
    }

    public function test_a_task_can_be_created()
    {
        $project = TestCase::createProject();
        $task = $project->tasks()->make(Task::factory()->raw());
        $this->post($project->path() . '/tasks', $task->getAttributes());

        $this->assertTrue($project->is($task->project));
        $this->assertCount(1, $project->tasks);
        $this->get($project->path())->assertSee($task->body);
    }

    public function test_a_task_must_have_a_body()
    {
        $project = TestCase::createProject();
        $task = $project->tasks()->make(['body' => '']);
        $this->post($project->path() . '/tasks', $task->getAttributes())->assertSessionHasErrors('body');
    }

}
