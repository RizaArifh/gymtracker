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
        $tasks = DailyTask::query()
            ->orderByDesc('task_date')
            ->latest('id')
            ->paginate(10);

        return view('daily_tasks.index', compact('tasks'));
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
}
