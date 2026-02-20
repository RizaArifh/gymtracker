<?php

namespace App\Http\Controllers;

use App\Models\BodyMeasurement;
use Illuminate\Http\Request;

class BodyMeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $measurements = BodyMeasurement::query()
            ->orderByDesc('measurement_date')
            ->paginate(10);

        return view('body_measurements.index', compact('measurements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('body_measurements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'measurement_date' => ['required', 'date'],
            'weight_kg' => ['required', 'numeric', 'min:1', 'max:999.99'],
            'body_fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'muscle_mass_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'chest_cm' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'waist_cm' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'hips_cm' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        BodyMeasurement::create($validated);

        return redirect()
            ->route('body-measurements.index')
            ->with('success', 'Measurement created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BodyMeasurement $bodyMeasurement)
    {
        return view('body_measurements.show', compact('bodyMeasurement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BodyMeasurement $bodyMeasurement)
    {
        return view('body_measurements.edit', compact('bodyMeasurement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BodyMeasurement $bodyMeasurement)
    {
        $validated = $request->validate([
            'measurement_date' => ['required', 'date'],
            'weight_kg' => ['required', 'numeric', 'min:1', 'max:999.99'],
            'body_fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'muscle_mass_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'chest_cm' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'waist_cm' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'hips_cm' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $bodyMeasurement->update($validated);

        return redirect()
            ->route('body-measurements.index')
            ->with('success', 'Measurement updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BodyMeasurement $bodyMeasurement)
    {
        $bodyMeasurement->delete();

        return redirect()
            ->route('body-measurements.index')
            ->with('success', 'Measurement deleted.');
    }
}
