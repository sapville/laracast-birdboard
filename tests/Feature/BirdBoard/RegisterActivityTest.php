<?php

namespace Tests\Feature\BirdBoard;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegisterActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_create()
    {
        static::logIn();
        $this->post('/projects', Project::factory()->raw());
        $project = Project::first();

        $this->assertCount(1, $project->activities);
        tap($project->activities->first(), function (Activity $activity) use ($project) {
            $this->assertDatabaseHas('activities', $activity->getAttributes());
            $this->assertEquals('created', $activity->description);
            $this->assertTrue($activity->recordable->is($project));
            $this->assertNull($activity->before);
            $this->assertEquals($project->getAttributes(), $activity->after);
        });
    }

    public function test_project_update()
    {
        DB::beginTransaction();
        $project = static::createProject();
        DB::commit();
        $old_project = $project->replicate();
        $project->title = Project::factory()->make()->title;
        $project->notes = Project::factory()->make()->notes;
        $this->patch($project->path(), $project->getAttributes());

        $this->assertCount(2, $project->activities);
        tap($project->activities->last(), function (Activity $activity) use ($project, $old_project) {
            $this->assertDatabaseHas('activities', $activity->getAttributes());
            $this->assertEquals('updated', $activity->description);
            $this->assertTrue($activity->recordable->is($project));
            $this->assertEquals(Arr::only($old_project->getAttributes(), array('title', 'notes')), $activity->before);
            $this->assertEquals(Arr::only($project->getAttributes(), array('title', 'notes')), $activity->after);
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
            $this->assertNull($activity->before);
            $this->assertEquals($task->getAttributes(), $activity->after);
        });

    }

    public function test_task_update()
    {
        $this->withoutExceptionHandling();

        list($project, $old_task, $new_task) = static::updateTask();

        $this->patch($old_task->path(), ['body' => $new_task->body]);
        $task = $project->fresh()->tasks->last();

        $this->assertCount(1, $project->activities);
        $this->assertCount(2, $task->activities);
        tap($task->activities->last(), function (Activity $activity) use ($task, $old_task) {
            $this->assertDatabaseHas('activities', $activity->getAttributes());
            $this->assertTrue($activity->recordable->is($task));
            $this->assertEquals('updated', $activity['description']);
            $this->assertEquals($old_task->body, $activity->before['body']);
            $this->assertEquals($task->body, $activity->after['body']);
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
            $this->assertEquals($task->getAttributes(), $activity->before);
            $this->assertNull($activity->after);
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
            $this->assertEquals(false, $activity->before['completed']);
            $this->assertEquals(true, $activity->after['completed']);
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
            $this->assertEquals(true, $activity->before['completed']);
            $this->assertEquals(false, $activity->after['completed']);
        });
    }

    public function test_task_updated_and_completed()
    {
        list($project, $old_task, $new_task) = static::updateTask();
        $this->patch($old_task->path(), $new_task->getAttributes());

        $this->assertCount(4, $project->activity);
        tap($old_task->activities[1], function ($activity) use ($old_task, $new_task) {
            $this->assertEquals('updated', $activity->description);
            $this->assertTrue($activity->recordable->is($old_task));
            $this->assertEquals($old_task->body, $activity->before['body']);
            $this->assertEquals($new_task->body, $activity->after['body']);
        });
        tap($old_task->activities[2], function ($activity) use ($old_task) {
            $this->assertEquals('completed', $activity->description);
            $this->assertTrue($activity->recordable->is($old_task));
            $this->assertEquals(false, $activity->before['completed']);
            $this->assertEquals(true, $activity->after['completed']);
        });

    }

    public function test_task_updated_and_uncompleted()
    {
        list($project, $old_task, $new_task) = static::updateTask();
        $old_task->complete();
        $new_task->completed = false;
        $this->patch($old_task->path(), $new_task->getAttributes());

        $this->assertCount(5, $project->activity);
        tap($old_task->activities[2], function ($activity) use ($old_task, $new_task) {
            $this->assertEquals('updated', $activity->description);
            $this->assertTrue($activity->recordable->is($old_task));
            $this->assertEquals($old_task->body, $activity->before['body']);
            $this->assertEquals($new_task->body, $activity->after['body']);

        });
        tap($old_task->activities[3], function ($activity) use ($old_task) {
            $this->assertEquals('uncompleted', $activity->description);
            $this->assertTrue($activity->recordable->is($old_task));
            $this->assertEquals(true, $activity->before['completed']);
            $this->assertEquals(false, $activity->after['completed']);
        });
    }
}

