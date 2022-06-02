<?php

namespace Tests\Unit\BirdBoard;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_task_has_a_path()
    {
        $project = TestCase::createProject();
        $task = $project->tasks()->create(Task::factory()->raw());

        $this->assertEquals( '/tasks/' . $task->id , $task->path());
    }

    public function test_a_task_can_be_completed()
    {
        $task = static::createProject()->addTask(Task::factory()->make()->body);

        $this->assertFalse($task->fresh()->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);

    }

    public function test_a_task_can_be_incompleted()
    {
        $task = static::createProject()->addTask(Task::factory()->make()->body);
        $task->complete();

        $this->assertTrue($task->fresh()->completed);
        $task->incomplete();
        $this->assertFalse($task->fresh()->completed);

    }
}
