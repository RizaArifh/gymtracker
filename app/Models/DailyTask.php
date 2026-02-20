<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTask extends Model
{
    protected $fillable = [
        'task_date',
        'title',
        'category',
        'duration_minutes',
        'calories_burned',
        'target_value',
        'target_unit',
        'is_completed',
        'notes',
    ];

    protected $casts = [
        'task_date' => 'date',
        'is_completed' => 'boolean',
    ];
}
