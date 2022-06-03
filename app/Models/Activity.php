<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['activityText'];

    public function activityText()
    {
        return $this->belongsTo(ActivityText::class, 'description', 'description');
    }
}
