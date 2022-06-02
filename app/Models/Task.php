<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use function Symfony\Component\Translation\t;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = [
        'completed' => 'boolean'
    ];

    protected static function booted()
    {
        static::created(function (Task $task) {
            $task->project->createActivity('task_created');
        });

        static::updated(function (Task $task) {
            $task->project->createActivity('task_updated');
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function path()
    {
        return '/tasks/' . $this->id;
    }

    public function complete()
    {
        $this->update(['completed' => true]);
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);
    }
}
