<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('calorie_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date')->index();
            $table->string('meal_type', 30);
            $table->string('food_name');
            $table->unsignedInteger('calories');
            $table->decimal('protein_grams', 6, 2)->nullable();
            $table->decimal('carbs_grams', 6, 2)->nullable();
            $table->decimal('fat_grams', 6, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calorie_entries');
    }
};
