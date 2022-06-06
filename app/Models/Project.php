<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function path(): string
    {
        return '/projects/' . $this->id;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activity(): HasMany
    {
        return $this->hasMany(Activity::class, 'project_id')->latest();
    }

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'recordable')->latest();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('completed')->orderByDesc('updated_at');
    }

    public function addTask(string $body): Task
    {
        return $this->tasks()->create(['body' => $body]);
    }

    public function createActivity(string $description, array|null $before, array|null $after)
    {
        $this->activities()->create([
            'project_id' => $this->id,
            'description' => $description,
            'before' => $before,
            'after' => $after,
        ]);
    }
}
