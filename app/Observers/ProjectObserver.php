<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Project;

class ProjectObserver
{
    public function created(Project $project)
    {
        $this->register($project, 'created');
    }

    public function updated(Project $project)
    {
        $this->register($project, 'updated');
    }

    protected function register(Project $project, string $description ): void
    {
        Activity::create([
            'project_id' => $project->id,
            'description' => $description,
        ]);
    }
}
