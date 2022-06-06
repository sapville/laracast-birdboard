<?php

namespace App\Observers;

use App\Models\Project;

class ProjectObserver
{
    protected static array $old_values = [];

    public function created(Project $project)
    {
        $project->createActivity('created', null, $project->getAttributes());
    }

    public function updating(Project $project)
    {
        static::$old_values = $project->getOriginal();//Project::query()->whereKey($project->id)->first()->toArray();
    }

    public function updated(Project $project)
    {
        $changes = $project->getChanges();
        $project->createActivity('updated', array_intersect_key(self::$old_values, $changes), $changes);
    }
}
