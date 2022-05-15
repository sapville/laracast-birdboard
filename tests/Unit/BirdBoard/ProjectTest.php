<?php

namespace Tests\Unit\BirdBoard;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    public function test_a_project_has_a_path()
    {
        $project = Project::factory()->make();
        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    public function test_a_project_assigned_to_a_user()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['owner_id' => $user->id]);

        $this->assertTrue($project->owner->is($user));
    }
}
