<?php

namespace App\Traits;


use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait RegisterActivity
{

    public array $old_values = [];

    protected static function booted()
    {
        static::updating(function (Model $model) {
            $model->old_values = $model->getOriginal();
        });

    }

    public function createActivity(string $description, array|null $before, array|null $after)
    {
        $project = $this->project ?: $this;
        $this->activities()->create([
            'user_id' => $project->owner->id,
            'project_id' => $project->id,
            'description' => $description,
            'before' => $before,
            'after' => $after,
        ]);
    }

    public function createUpdatedActivity(Model $model, string $description = 'updated')
    {
        $changes = $model->getChanges();
        $model->createActivity($description, array_intersect_key($model->old_values, $changes), $changes);
    }
}
