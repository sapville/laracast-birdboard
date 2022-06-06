<?php

namespace App\Observers;

use App\Models\Project;
use App\Traits\RegisterActivity;

class ProjectObserver
{
    use RegisterActivity;

    public function created(Project $project)
    {
        $project->createActivity('created', null, $project->getAttributes());
    }

    public function updated(Project $project)
    {
        $this->createUpdatedActivity($project);
    }
}
