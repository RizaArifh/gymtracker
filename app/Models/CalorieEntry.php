<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalorieEntry extends Model
{
    protected $fillable = [
        'entry_date',
        'meal_type',
        'food_name',
        'calories',
        'protein_grams',
        'carbs_grams',
        'fat_grams',
        'notes',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];
}
