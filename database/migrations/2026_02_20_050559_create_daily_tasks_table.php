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
        Schema::create('daily_tasks', function (Blueprint $table) {
            $table->id();
            $table->date('task_date')->index();
            $table->string('title');
            $table->string('category')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedInteger('calories_burned')->default(0);
            $table->decimal('target_value', 8, 2)->nullable();
            $table->string('target_unit', 20)->nullable();
            $table->boolean('is_completed')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_tasks');
    }
};
