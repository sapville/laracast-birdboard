<?php

namespace Tests\Feature\BirdBoard;

use App\Models\Activity;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_create()
    {
        $project = static::createProject();

        $this->assertCount(1, $project->activities);
        tap($project->activities->first(), function (Activity $activity) use ($project) {
            $this->assertDatabaseHas('activities', $activity->getAttributes());
            $this->assertEquals('created', $activity->description);
            $this->assertTrue($activity->recordable->is($project));
        });
    }

    public function test_project_update()
    {
        $project = static::updateProject();
        $project->update();

        $this->assertCount(2, $project->activities);
        tap($project->activities->last(), function (Activity $activity) use ($project) {
            $this->assertDatabaseHas('activities', $activity->getAttributes());
            $this->assertEquals('updated', $activity->description);
            $this->assertTrue($activity->recordable->is($project));
        });

    }

    public function test_task_create()
    {
        $project = static::createProject();

        $this->post($project->path() . '/tasks', Task::factory()->raw());
        $task = $project->fresh()->tasks->last();

        $this->assertCount(1, $task->activities);
        $this->assertCount(1, $project->activities);
        tap($task->activities->last(), function (Activity $activity) use ($task) {
            $this->assertDatabaseHas('activities', $activity->getAttributes());
            $this->assertEquals('created', $activity->description);
            $this->assertTrue($activity->recordable->is($task));
        });

    }

    public function test_task_update()
    {
        list($project, $old_task, $new_task) = static::updateTask();

        $this->patch($old_task->path(), ['body' => $new_task->body]);
        $task = $project->fresh()->tasks->last();

        $this->assertCount(1, $project->activities);
        $this->assertCount(2, $task->activities);
        tap($task->activities->last(), function (Activity $activity) use ($task) {
            $this->assertDatabaseHas('activities', $activity->getAttributes());
            $this->assertTrue($activity->recordable->is($task));
            $this->assertEquals('updated', $activity['description']);
        });

    }

    public function test_task_deleted()
    {
        list($project, $task,) = static::updateTask();

        $task->delete();

        $this->assertCount(1, $project->activities);
        $this->assertCount(2, $task->activities);
        $this->assertCount(3, $project->activity);
        tap($task->activities->last(), function (Activity $activity) use ($task) {
            $this->assertDatabaseHas('activities', $activity->getAttributes());
            $this->assertEquals(Task::class, $activity->recordable_type);
            $this->assertEquals('deleted', $activity['description']);
        });

    }

    public function test_task_completed()
    {
        list($project, $task,) = static::updateTask();
        $task->completed = true;
        $this->patch($task->path(), $task->getAttributes());

        $this->assertCount(3, $project->activity);
        $this->assertCount(2, $task->activities);
        tap($task->activities->last(), function (Activity $activity) use ($task) {
            $this->assertDatabaseHas('activities', $activity->getAttributes());
            $this->assertEquals('completed', $activity['description']);
            $this->assertTrue(($activity->recordable->is($task)));
        });

    }

    public function test_task_uncompleted()
    {
        list($project, $task,) = static::updateTask();
        $task->complete();
        $task->completed = false;
        $this->patch($task->path(), $task->getAttributes());


        $this->assertCount(4, $project->activity);
        $this->assertCount(3, $task->activities);
        tap($task->activities->last(), function (Activity $activity) use ($task) {
            $this->assertDatabaseHas('activities', $activity->getAttributes());
            $this->assertEquals('uncompleted', $activity['description']);
            $this->assertTrue(($activity->recordable->is($task)));
        });
    }

    public function test_task_updated_and_completed()
    {
        list($project, $old_task, $new_task) = static::updateTask();
        $this->patch($old_task->path(), $new_task->getAttributes());

        $this->assertCount(4, $project->activity);
        tap($old_task->activities[1], function ($activity) use ($old_task) {
            $this->assertEquals('updated', $activity->description);
            $this->assertTrue($activity->recordable->is($old_task));
        });
        tap($old_task->activities[2], function ($activity) use ($old_task) {
            $this->assertEquals('completed', $activity->description);
            $this->assertTrue($activity->recordable->is($old_task));
        });

    }

    public function test_task_updated_and_uncompleted()
    {
        list($project, $old_task, $new_task) = static::updateTask();
        $old_task->complete();
        $new_task->completed = false;
        $this->patch($old_task->path(), $new_task->getAttributes());

        $this->assertCount(5, $project->activity);
        tap($old_task->activities[2], function ($activity) use ($old_task) {
            $this->assertEquals('updated', $activity->description);
            $this->assertTrue($activity->recordable->is($old_task));
        });
        tap($old_task->activities[3], function ($activity) use ($old_task) {
            $this->assertEquals('uncompleted', $activity->description);
            $this->assertTrue($activity->recordable->is($old_task));
        });
    }
}

