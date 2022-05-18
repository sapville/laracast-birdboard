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

        $this->assertEquals( $project->path() . '/tasks/' . $task->id , $task->path());
    }
}
