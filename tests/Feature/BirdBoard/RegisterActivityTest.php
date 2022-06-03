<?php

namespace Tests\Feature\BirdBoard;

use App\Models\Task;
use Database\Seeders\ActivityTextSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterActivityTest extends TestCase
{
    use RefreshDatabase;

    protected $seeder = ActivityTextSeeder::class;

    public function test_project_create()
    {
        $project = static::createProject();
        $activity = $project->activities->first()->getAttributes();

        $this->assertCount(1, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('project_created', $activity['description']);
    }

    public function test_project_update()
    {
        $project = static::updateProject();
        $project->update();
        $activity = $project->activities->last()->getAttributes();

        $this->assertCount(2, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('project_updated', $activity['description']);

    }

    public function test_task_create()
    {
        $project = static::createProject();
        $task = Task::factory()->raw();

        $this->post($project->path() . '/tasks', $task);
        $activity = $project->activities->last()->getAttributes();

        $this->assertCount(2, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('task_created', $activity['description']);

    }

    public function test_task_update()
    {
        list($project, $old_task, $new_task) = static::updateTask();

        $this->patch($old_task->path(), ['body' => $new_task->body]);
        $activity = $project->activities->last()->getAttributes();

        $this->assertCount(3, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('task_updated', $activity['description']);

    }

    public function test_task_deleted()
    {
        list($project, $old_task, ) = static::updateTask();

        $old_task->delete();
        $activity = $project->activities->last()->getAttributes();

        $this->assertCount(3, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('task_deleted', $activity['description']);

    }


    public function test_task_completed()
    {
        list($project, $old_task, ) = static::updateTask();
        $old_task->completed = true;
        $this->patch($old_task->path(), $old_task->getAttributes());
        $activity = $project->activities->last()->getAttributes();

        $this->assertCount(3, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('task_completed', $activity['description']);

    }

    public function test_task_uncompleted()
    {
        list($project, $old_task, ) = static::updateTask();
        $old_task->complete();
        $old_task->completed = false;
        $this->patch($old_task->path(), $old_task->getAttributes());

        $activity = $project->activities->last()->getAttributes();

        $this->assertCount(4, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('task_uncompleted', $activity['description']);
    }

    public function test_task_updated_and_completed()
    {
        list($project, $old_task, $new_task) = static::updateTask();
        $this->patch($old_task->path(), $new_task->getAttributes());

        $this->assertCount(4, $project->activities);
        $this->assertEquals('task_updated', $project->activities[2]->description);
        $this->assertEquals('task_completed', $project->activities[3]->description);

    }

    public function test_task_updated_and_uncompleted()
    {
        list($project, $old_task, $new_task) = static::updateTask();
        $old_task->complete();
        $new_task->completed = false;
        $this->patch($old_task->path(), $new_task->getAttributes());

        $this->assertCount(5, $project->activities);
        $this->assertEquals('task_updated', $project->activities[3]->description);
        $this->assertEquals('task_uncompleted', $project->activities[4]->description);
    }
}

