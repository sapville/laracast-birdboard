<?php

namespace Tests\Unit;

use App\Models\Project;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    public function test_a_project_has_a_path()
    {
        $project = Project::factory()->make();
        $this->assertEquals('/projects/' . $project->id, $project->path());
    }
}
