<?php

namespace App\Http\Controllers;

use App\Models\CalorieEntry;
use Illuminate\Http\Request;

class CalorieEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entries = CalorieEntry::query()
            ->orderByDesc('entry_date')
            ->latest('id')
            ->paginate(10);

        return view('calorie_entries.index', compact('entries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('calorie_entries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'entry_date' => ['required', 'date'],
            'meal_type' => ['required', 'string', 'max:30'],
            'food_name' => ['required', 'string', 'max:255'],
            'calories' => ['required', 'integer', 'min:0', 'max:99999'],
            'protein_grams' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'carbs_grams' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'fat_grams' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        CalorieEntry::create($validated);

        return redirect()
            ->route('calorie-entries.index')
            ->with('success', 'Calorie entry created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CalorieEntry $calorieEntry)
    {
        return view('calorie_entries.show', compact('calorieEntry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CalorieEntry $calorieEntry)
    {
        return view('calorie_entries.edit', compact('calorieEntry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CalorieEntry $calorieEntry)
    {
        $validated = $request->validate([
            'entry_date' => ['required', 'date'],
            'meal_type' => ['required', 'string', 'max:30'],
            'food_name' => ['required', 'string', 'max:255'],
            'calories' => ['required', 'integer', 'min:0', 'max:99999'],
            'protein_grams' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'carbs_grams' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'fat_grams' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $calorieEntry->update($validated);

        return redirect()
            ->route('calorie-entries.index')
            ->with('success', 'Calorie entry updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CalorieEntry $calorieEntry)
    {
        $calorieEntry->delete();

        return redirect()
            ->route('calorie-entries.index')
            ->with('success', 'Calorie entry deleted.');
    }
}
