<?php

namespace Tests\Feature\BirdBoard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_create_activity_is_registered()
    {
        $project = TestCase::createProject();
        $activity = $project->activities->first()->getAttributes();

        $this->assertCount(1, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('created', $activity['description']);
    }

    public function test_project_update_activity_is_registered()
    {
        $project = TestCase::updateProject();
        $project->update();
        $activity = $project->activities->last()->getAttributes();

        $this->assertCount(2, $project->activities);
        $this->assertDatabaseHas('activities', $activity);
        $this->assertEquals('updated', $activity['description']);

    }
}

