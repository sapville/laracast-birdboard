<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'before' => 'array',
        'after' => 'array'
    ];

    public function recordable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function showActivitySubject(): string
    {
        $changes = [];
        switch ($this->recordable_type) {
            case Project::class:
                if ($this->before && $this->after)
                    $changes = array_keys(Arr::except($this->after, ['updated_at', 'created_at']));
                if (count($changes) === 1)
                    return $changes[0];
                return 'project';
            case Task::class:
                return $this->recordable->body;
            default:
                return 'unknown';
        }
    }
}
