<?php

namespace App\Http\Controllers;

use App\Models\BodyMeasurement;
use App\Services\BodyMeasurementImageParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

        $best = $this->extractBestMeasurementFromImage($absolutePath, $parser);
        $ocrText = $best['ocr_text'];
        $parsed = $best['parsed'];

        $warning = null;
        if (empty($parsed['weight_kg'])) {
            $warning = 'OCR belum menemukan berat dengan yakin. Silakan koreksi manual di popup review.';
        }

        return response()->json([
            'message' => 'Preview OCR berhasil dibuat.',
            'warning' => $warning,
            'measurement_date' => $validated['measurement_date'] ?? now()->toDateString(),
            'source_image_path' => $path,
            'source_image_url' => Storage::url($path),
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
        $process = new Process([$this->resolveTesseractBinary(), '--version']);
        $process->run();

        return $process->isSuccessful();
    }

    private function runTesseract(string $filePath): string
    {
        $binary = $this->resolveTesseractBinary();
        $modes = ['6', '11', '4'];
        $outputs = [];

        foreach ($modes as $psm) {
            $process = new Process([
                $binary,
                $filePath,
                'stdout',
                '-l',
                'eng',
                '--oem',
                '1',
                '--psm',
                $psm,
                '-c',
                'preserve_interword_spaces=1',
            ]);
            $process->setTimeout(60);
            $process->run();

            if ($process->isSuccessful()) {
                $text = trim($process->getOutput());
                if ($text !== '') {
                    $outputs[] = $text;
                }
            }
        }

        if (empty($outputs)) {
            return '';
        }

        return trim(implode("\n", array_unique($outputs)));
    }

    private function extractBestMeasurementFromImage(string $filePath, BodyMeasurementImageParser $parser): array
    {
        $candidates = $this->buildImageCandidates($filePath);
        $bestParsed = [];
        $bestOcr = '';
        $bestScore = -1;

        foreach ($candidates as $candidatePath) {
            $ocrText = $this->runTesseract($candidatePath);
            if ($ocrText === '') {
                continue;
            }

            $parsed = $parser->parse($ocrText);
            $score = $this->scoreParsedResult($parsed);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestParsed = $parsed;
                $bestOcr = $ocrText;
            }
        }

        foreach ($candidates as $candidatePath) {
            if ($candidatePath !== $filePath && str_contains($candidatePath, 'ocr-temp')) {
                @unlink($candidatePath);
            }
        }

        return [
            'parsed' => $bestParsed,
            'ocr_text' => $bestOcr,
        ];
    }

    private function scoreParsedResult(array $parsed): int
    {
        $priority = ['weight_kg', 'body_fat_percentage', 'height_cm', 'bmi'];
        $score = 0;

        foreach ($parsed as $key => $value) {
            if ($value !== null && $value !== '') {
                $score += in_array($key, $priority, true) ? 3 : 1;
            }
        }

        if (! empty($parsed['weight_kg'])) {
            $score += 5;
        }

        return $score;
    }

    private function buildImageCandidates(string $sourcePath): array
    {
        $candidates = [$sourcePath];
        if (! function_exists('imagecreatefromstring')) {
            return $candidates;
        }

        $raw = @file_get_contents($sourcePath);
        if ($raw === false) {
            return $candidates;
        }

        $img = @imagecreatefromstring($raw);
        if (! $img) {
            return $candidates;
        }

        $width = imagesx($img);
        $height = imagesy($img);
        $tmpDir = storage_path('app/ocr-temp');
        if (! is_dir($tmpDir)) {
            @mkdir($tmpDir, 0775, true);
        }

        foreach ([-4, -2, 2, 4] as $angle) {
            $rotated = @imagerotate($img, $angle, 0);
            if (! $rotated) {
                continue;
            }

            $rotatedPath = $tmpDir.'/'.Str::uuid().'_rot_'.$angle.'.png';
            imagepng($rotated, $rotatedPath);
            imagedestroy($rotated);
            $candidates[] = $rotatedPath;
        }

        $cropWidth = max(200, (int) floor($width * 0.42));
        $cropHeight = max(200, (int) floor($height * 0.93));
        $leftCrop = @imagecrop($img, ['x' => 0, 'y' => 0, 'width' => $cropWidth, 'height' => $cropHeight]);
        if ($leftCrop) {
            $cropPath = $tmpDir.'/'.Str::uuid().'_left_crop.png';
            imagepng($leftCrop, $cropPath);
            $candidates[] = $cropPath;

            @imagefilter($leftCrop, IMG_FILTER_GRAYSCALE);
            @imagefilter($leftCrop, IMG_FILTER_CONTRAST, -12);
            @imagefilter($leftCrop, IMG_FILTER_BRIGHTNESS, 8);
            $enhancedCropPath = $tmpDir.'/'.Str::uuid().'_left_crop_enhanced.png';
            imagepng($leftCrop, $enhancedCropPath);
            $candidates[] = $enhancedCropPath;

            foreach ([-3, 3] as $angle) {
                $rotatedCrop = @imagerotate($leftCrop, $angle, 0);
                if (! $rotatedCrop) {
                    continue;
                }
                $rotatedCropPath = $tmpDir.'/'.Str::uuid().'_left_rot_'.$angle.'.png';
                imagepng($rotatedCrop, $rotatedCropPath);
                imagedestroy($rotatedCrop);
                $candidates[] = $rotatedCropPath;
            }

            imagedestroy($leftCrop);
        }

        imagedestroy($img);

        return array_values(array_unique($candidates));
    }

    private function resolveTesseractBinary(): string
    {
        $fromEnv = env('TESSERACT_PATH');
        if (is_string($fromEnv) && $fromEnv !== '') {
            return $fromEnv;
        }

        $windowsDefault = 'C:\\Program Files\\Tesseract-OCR\\tesseract.exe';
        if (PHP_OS_FAMILY === 'Windows' && file_exists($windowsDefault)) {
            return $windowsDefault;
        }

        return 'tesseract';
    }
}



