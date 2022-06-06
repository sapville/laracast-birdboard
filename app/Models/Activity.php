<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
}
