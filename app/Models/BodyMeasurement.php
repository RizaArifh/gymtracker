<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyMeasurement extends Model
{
    protected $fillable = [
        'measurement_date',
        'weight_kg',
        'body_fat_percentage',
        'muscle_mass_kg',
        'chest_cm',
        'waist_cm',
        'hips_cm',
        'notes',
    ];

    protected $casts = [
        'measurement_date' => 'date',
    ];
}
