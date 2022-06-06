<?php

namespace App\Traits;


use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;

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
        $this->activities()->create([
            'project_id' => get_class($this) === Project::class ? $this->id : $this->project_id,
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
