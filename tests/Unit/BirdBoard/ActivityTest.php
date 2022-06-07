<?php

namespace Tests\Unit\BirdBoard;

use App\Models\Activity;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_activity_can_show_subject()
    {
        $project = self::updateProject();
        $this->assertEquals('project', Activity::all()[0]->showActivitySubject());
        $project->update();
        $this->assertEquals('project', Activity::all()[1]->showActivitySubject());
        $project->update(['title' => Project::factory()->make()->title]);
        $this->assertEquals('title', Activity::all()[2]->showActivitySubject());
        list(, $oldTask, $newTask) = self::updateTask($project);
        $this->assertEquals($oldTask->body, Activity::all()[3]->showActivitySubject());
        $oldTask->update($newTask->getAttributes());
        $this->assertEquals($oldTask->body, Activity::all()[4]->showActivitySubject());
        $oldTask->complete();
        $this->assertEquals($oldTask->body, Activity::all()[5]->showActivitySubject());
        $oldTask->incomplete();
        $this->assertEquals($oldTask->body, Activity::all()[6]->showActivitySubject());
    }

    public function test_activity_has_an_owner()
    {
        list($project, $oldTask) = self::updateTask();
        $this->assertEquals(Auth::id(), $project->activities->first()->user->id);
        $this->assertEquals(Auth::id(), $oldTask->activities->first()->user->id);
    }
}
