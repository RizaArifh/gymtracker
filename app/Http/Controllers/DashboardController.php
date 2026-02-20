<?php

namespace App\Http\Controllers;

use App\Models\BodyMeasurement;
use App\Models\CalorieEntry;
use App\Models\DailyTask;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = now()->toDateString();

        $todayCaloriesIn = CalorieEntry::query()
            ->whereDate('entry_date', $today)
            ->sum('calories');

        $todayCaloriesBurned = DailyTask::query()
            ->whereDate('task_date', $today)
            ->sum('calories_burned');

        $todayTaskTotal = DailyTask::query()
            ->whereDate('task_date', $today)
            ->count();

        $todayTaskCompleted = DailyTask::query()
            ->whereDate('task_date', $today)
            ->where('is_completed', true)
            ->count();

        $latestMeasurement = BodyMeasurement::query()
            ->orderByDesc('measurement_date')
            ->first();

        $weeklyMeasurements = BodyMeasurement::query()
            ->where('measurement_date', '>=', now()->subDays(6)->toDateString())
            ->orderBy('measurement_date')
            ->get(['measurement_date', 'weight_kg']);

        $recentCalorieEntries = CalorieEntry::query()
            ->orderByDesc('entry_date')
            ->latest('id')
            ->limit(5)
            ->get();

        $recentTasks = DailyTask::query()
            ->orderByDesc('task_date')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'today' => $today,
            'todayCaloriesIn' => $todayCaloriesIn,
            'todayCaloriesBurned' => $todayCaloriesBurned,
            'todayNetCalories' => $todayCaloriesIn - $todayCaloriesBurned,
            'todayTaskTotal' => $todayTaskTotal,
            'todayTaskCompleted' => $todayTaskCompleted,
            'latestMeasurement' => $latestMeasurement,
            'weeklyMeasurements' => $weeklyMeasurements,
            'recentCalorieEntries' => $recentCalorieEntries,
            'recentTasks' => $recentTasks,
        ]);
    }
}
