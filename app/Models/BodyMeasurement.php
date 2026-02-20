<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyMeasurement extends Model
{
    protected $fillable = [
        'measurement_date',
        'height_cm',
        'weight_kg',
        'body_fat_percentage',
        'muscle_mass_kg',
        'bmi',
        'visceral_fat_grade',
        'fat_mass_kg',
        'lean_body_mass_kg',
        'evaluation_score',
        'physical_age',
        'chest_cm',
        'waist_cm',
        'hips_cm',
        'source_image_path',
        'source_ocr_text',
        'notes',
    ];

    protected $casts = [
        'measurement_date' => 'date',
    ];
}
