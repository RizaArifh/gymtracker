<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('body_measurements', function (Blueprint $table) {
            $table->decimal('height_cm', 6, 2)->nullable()->after('measurement_date');
            $table->decimal('bmi', 5, 2)->nullable()->after('muscle_mass_kg');
            $table->unsignedInteger('visceral_fat_grade')->nullable()->after('bmi');
            $table->decimal('fat_mass_kg', 6, 2)->nullable()->after('visceral_fat_grade');
            $table->decimal('lean_body_mass_kg', 6, 2)->nullable()->after('fat_mass_kg');
            $table->decimal('evaluation_score', 5, 2)->nullable()->after('lean_body_mass_kg');
            $table->unsignedInteger('physical_age')->nullable()->after('evaluation_score');
            $table->string('source_image_path')->nullable()->after('physical_age');
            $table->text('source_ocr_text')->nullable()->after('source_image_path');
        });
    }

    public function down(): void
    {
        Schema::table('body_measurements', function (Blueprint $table) {
            $table->dropColumn([
                'height_cm',
                'bmi',
                'visceral_fat_grade',
                'fat_mass_kg',
                'lean_body_mass_kg',
                'evaluation_score',
                'physical_age',
                'source_image_path',
                'source_ocr_text',
            ]);
        });
    }
};

