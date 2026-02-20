<?php

namespace App\Http\Controllers;

use App\Models\DailyTask;
use Illuminate\Http\Request;

class DailyTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = DailyTask::query();

        if (request('status') === 'done') {
            $query->where('is_completed', true);
        } elseif (request('status') === 'pending') {
            $query->where('is_completed', false);
        }

        if (filled(request('task_date'))) {
            $query->whereDate('task_date', request('task_date'));
        }

        if (filled(request('q'))) {
            $term = request('q');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%'.$term.'%')
                    ->orWhere('category', 'like', '%'.$term.'%');
            });
        }

        $tasks = $query
            ->orderByDesc('task_date')
            ->latest('id')
            ->paginate(10);

        $statsQuery = DailyTask::query();
        if (filled(request('task_date'))) {
            $statsQuery->whereDate('task_date', request('task_date'));
        }
        $totalTasks = (clone $statsQuery)->count();
        $completedTasks = (clone $statsQuery)->where('is_completed', true)->count();
        $completionPercentage = $totalTasks > 0
            ? (int) round(($completedTasks / $totalTasks) * 100)
            : 0;

        return view('daily_tasks.index', [
            'tasks' => $tasks,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'completionPercentage' => $completionPercentage,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('daily_tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'calories_burned' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'target_value' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'target_unit' => ['nullable', 'string', 'max:20'],
            'is_completed' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['calories_burned'] = $validated['calories_burned'] ?? 0;
        $validated['is_completed'] = $request->boolean('is_completed');

        DailyTask::create($validated);

        return redirect()
            ->route('daily-tasks.index')
            ->with('success', 'Daily task created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyTask $dailyTask)
    {
        return view('daily_tasks.show', compact('dailyTask'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyTask $dailyTask)
    {
        return view('daily_tasks.edit', compact('dailyTask'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyTask $dailyTask)
    {
        $validated = $request->validate([
            'task_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'duration_minutes' => ['nullable', 'integer', 'min:0', 'max:1440'],
            'calories_burned' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'target_value' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'target_unit' => ['nullable', 'string', 'max:20'],
            'is_completed' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['calories_burned'] = $validated['calories_burned'] ?? 0;
        $validated['is_completed'] = $request->boolean('is_completed');

        $dailyTask->update($validated);

        return redirect()
            ->route('daily-tasks.index')
            ->with('success', 'Daily task updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyTask $dailyTask)
    {
        $dailyTask->delete();

        return redirect()
            ->route('daily-tasks.index')
            ->with('success', 'Daily task deleted.');
    }

    public function toggle(DailyTask $dailyTask)
    {
        request()->validate([
            'is_completed' => ['required', 'boolean'],
        ]);

        $dailyTask->update([
            'is_completed' => request()->boolean('is_completed'),
        ]);

        $taskDate = $dailyTask->task_date?->toDateString();
        $stats = DailyTask::query()
            ->whereDate('task_date', $taskDate)
            ->selectRaw('count(*) as total_count')
            ->selectRaw('sum(case when is_completed = 1 then 1 else 0 end) as completed_count')
            ->first();

        $totalCount = (int) ($stats->total_count ?? 0);
        $completedCount = (int) ($stats->completed_count ?? 0);
        $completionPercentage = $totalCount > 0
            ? (int) round(($completedCount / $totalCount) * 100)
            : 0;

        if (! request()->expectsJson()) {
            return redirect()
                ->route('daily-tasks.index')
                ->with('success', 'Task status updated.');
        }

        return response()->json([
            'id' => $dailyTask->id,
            'is_completed' => (bool) $dailyTask->is_completed,
            'total_count' => $totalCount,
            'completed_count' => $completedCount,
            'completion_percentage' => $completionPercentage,
        ]);
    }
}
