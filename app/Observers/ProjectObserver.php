<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Project;

class ProjectObserver
{
    public function created(Project $project)
    {
        $project->createActivity('project_created');
    }

    public function updated(Project $project)
    {
        $project->createActivity('project_updated');
    }
}
