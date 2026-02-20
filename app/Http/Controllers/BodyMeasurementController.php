<?php

namespace App\Http\Controllers;

use App\Models\BodyMeasurement;
use App\Services\BodyMeasurementImageParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

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
            'height_cm' => ['nullable', 'numeric', 'min:1', 'max:300'],
            'weight_kg' => ['required', 'numeric', 'min:1', 'max:999.99'],
            'body_fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'muscle_mass_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'bmi' => ['nullable', 'numeric', 'min:1', 'max:99.99'],
            'visceral_fat_grade' => ['nullable', 'integer', 'min:0', 'max:100'],
            'fat_mass_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'lean_body_mass_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'evaluation_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'physical_age' => ['nullable', 'integer', 'min:0', 'max:120'],
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
            'height_cm' => ['nullable', 'numeric', 'min:1', 'max:300'],
            'weight_kg' => ['required', 'numeric', 'min:1', 'max:999.99'],
            'body_fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'muscle_mass_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'bmi' => ['nullable', 'numeric', 'min:1', 'max:99.99'],
            'visceral_fat_grade' => ['nullable', 'integer', 'min:0', 'max:100'],
            'fat_mass_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'lean_body_mass_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'evaluation_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'physical_age' => ['nullable', 'integer', 'min:0', 'max:120'],
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

    public function importFromImage(Request $request, BodyMeasurementImageParser $parser)
    {
        $validated = $request->validate([
            'measurement_date' => ['nullable', 'date'],
            'measurement_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        if (! $this->isTesseractAvailable()) {
            return response()->json([
                'message' => 'OCR belum bisa jalan karena Tesseract belum terinstall. Install dulu: winget install UB-Mannheim.TesseractOCR',
            ], 422);
        }

        $path = $request->file('measurement_image')->store('body-measurements', 'public');
        $absolutePath = $request->file('measurement_image')->getRealPath();

        $ocrText = $this->runTesseract($absolutePath);
        $parsed = $parser->parse($ocrText);

        if (empty($parsed['weight_kg'])) {
            return response()->json([
                'message' => 'Gagal membaca berat badan dari gambar. Coba foto lebih jelas/lurus.',
            ], 422);
        }

        return response()->json([
            'message' => 'Preview OCR berhasil dibuat.',
            'measurement_date' => $validated['measurement_date'] ?? now()->toDateString(),
            'source_image_path' => $path,
            'source_ocr_text' => $ocrText,
            'parsed' => $parsed,
        ]);
    }

    public function storeImportedPreview(Request $request)
    {
        $validated = $request->validate([
            'measurement_date' => ['required', 'date'],
            'height_cm' => ['nullable', 'numeric', 'min:1', 'max:300'],
            'weight_kg' => ['required', 'numeric', 'min:1', 'max:999.99'],
            'body_fat_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'muscle_mass_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'bmi' => ['nullable', 'numeric', 'min:1', 'max:99.99'],
            'visceral_fat_grade' => ['nullable', 'integer', 'min:0', 'max:100'],
            'fat_mass_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'lean_body_mass_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'evaluation_score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'physical_age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'source_image_path' => ['required', 'string'],
            'source_ocr_text' => ['nullable', 'string'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if (! str_starts_with($validated['source_image_path'], 'body-measurements/')) {
            return back()->withErrors([
                'measurement_image' => 'Path file source tidak valid.',
            ])->withInput();
        }

        if (! Storage::disk('public')->exists($validated['source_image_path'])) {
            return back()->withErrors([
                'measurement_image' => 'Gagal membaca berat badan dari gambar. Coba foto lebih jelas/lurus.',
            ])->withInput();
        }

        $validated['notes'] = trim(($validated['notes'] ?? '')."\nImported from image OCR");
        BodyMeasurement::create($validated);

        return redirect()
            ->route('body-measurements.index')
            ->with('success', 'Measurement berhasil direview dan disimpan.');
    }

    private function isTesseractAvailable(): bool
    {
        $process = new Process(['tesseract', '--version']);
        $process->run();

        return $process->isSuccessful();
    }

    private function runTesseract(string $filePath): string
    {
        $process = new Process([
            'tesseract',
            $filePath,
            'stdout',
            '-l',
            'eng',
            '--psm',
            '6',
        ]);
        $process->setTimeout(45);
        $process->run();

        if (! $process->isSuccessful()) {
            return '';
        }

        return trim($process->getOutput());
    }
}
