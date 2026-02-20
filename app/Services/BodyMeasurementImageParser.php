<?php

namespace App\Services;

class BodyMeasurementImageParser
{
    public function parse(string $ocrText): array
    {
        $normalized = $this->normalize($ocrText);

        return [
            'height_cm' => $this->firstFloat($normalized, [
                '/height[^\d]{0,20}(\d{2,3}(?:\.\d+)?)/i',
            ]),
            'weight_kg' => $this->firstFloat($normalized, [
                '/weight[^\d]{0,20}(\d{2,3}(?:\.\d+)?)/i',
            ]),
            'body_fat_percentage' => $this->firstFloat($normalized, [
                '/fat\s*percentage[^\d]{0,20}(\d{1,3}(?:\.\d+)?)/i',
            ]),
            'muscle_mass_kg' => $this->firstFloat($normalized, [
                '/muscle\s*mass[^\d]{0,20}(\d{1,3}(?:\.\d+)?)/i',
            ]),
            'bmi' => $this->firstFloat($normalized, [
                '/bmi[^\d]{0,10}(\d{1,3}(?:\.\d+)?)/i',
            ]),
            'visceral_fat_grade' => $this->firstInt($normalized, [
                '/visceral\s*fat\s*grade[^\d]{0,15}(\d{1,3})/i',
            ]),
            'fat_mass_kg' => $this->firstFloat($normalized, [
                '/fat\s*mass[^\d]{0,20}(\d{1,3}(?:\.\d+)?)/i',
            ]),
            'lean_body_mass_kg' => $this->firstFloat($normalized, [
                '/lean\s*body\s*mass[^\d]{0,20}(\d{1,3}(?:\.\d+)?)/i',
            ]),
            'evaluation_score' => $this->firstFloat($normalized, [
                '/evaluation[^\d]{0,10}(\d{1,3}(?:\.\d+)?)/i',
            ]),
            'physical_age' => $this->firstInt($normalized, [
                '/physical\s*age[^\d]{0,10}(\d{1,3})/i',
            ]),
        ];
    }

    private function normalize(string $text): string
    {
        $text = str_replace(',', '.', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        return (string) $text;
    }

    private function firstFloat(string $text, array $patterns): ?float
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return isset($matches[1]) ? (float) $matches[1] : null;
            }
        }

        return null;
    }

    private function firstInt(string $text, array $patterns): ?int
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return isset($matches[1]) ? (int) $matches[1] : null;
            }
        }

        return null;
    }
}

