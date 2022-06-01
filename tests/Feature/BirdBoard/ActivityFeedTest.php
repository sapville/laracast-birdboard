<?php

namespace Tests\Feature\BirdBoard;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_create_activity_is_registered()
    {
        $project = static::createProject();
        $activity = $project->activities->first()->getAttributes();

        $this->assertCount(1, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('created', $activity['description']);
    }

    public function test_project_update_activity_is_registered()
    {
        $project = static::updateProject();
        $project->update();
        $activity = $project->activities->last()->getAttributes();

        $this->assertCount(2, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('updated', $activity['description']);

    }

    public function test_task_create_activity_is_registered()
    {
        $project = static::createProject();
        $task = Task::factory()->raw();

        $this->post($project->path() . '/tasks', $task);
        $activity = $project->activities->last()->getAttributes();

        $this->assertCount(2, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('task_created', $activity['description']);

    }

    public function test_task_update_activity_is_registered()
    {
        list($project, $old_task, $new_task) = static::updateTask();

        $this->patch($old_task->path(), $new_task->getAttributes());
        $activity = $project->activities->last()->getAttributes();

        $this->assertCount(3, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('task_updated', $activity['description']);

    }



}

