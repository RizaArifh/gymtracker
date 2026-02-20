<?php

use App\Http\Controllers\BodyMeasurementController;
use App\Http\Controllers\CalorieEntryController;
use App\Http\Controllers\DailyTaskController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::post('daily-tasks/{dailyTask}/toggle', [DailyTaskController::class, 'toggle'])
    ->name('daily-tasks.toggle');

Route::resource('body-measurements', BodyMeasurementController::class);
Route::resource('calorie-entries', CalorieEntryController::class);
Route::resource('daily-tasks', DailyTaskController::class);
