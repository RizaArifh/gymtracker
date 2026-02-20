<?php

namespace App\Services;

class BodyMeasurementImageParser
{
    public function parse(string $ocrText): array
    {
        $normalized = $this->normalize($ocrText);

        return [
            'height_cm' => $this->firstFloatInRange($normalized, [
                '/h[e3][i1l][gq][h]?t[^\d]{0,24}(\d{2,3}(?:\.\d+)?)/i',
                '/height[^\d]{0,24}(\d{2,3}(?:\.\d+)?)/i',
            ]),
            'weight_kg' => $this->firstFloatInRange($normalized, [
                '/w[e3][i1l]g?[h]?t[^\d]{0,24}(\d{2,3}(?:\.\d+)?)/i',
                '/weight[^\d]{0,24}(\d{2,3}(?:\.\d+)?)/i',
                '/\bweight\b[^\d]{0,24}(\d{2,3}(?:\.\d+)?)/i',
            ]),
            'body_fat_percentage' => $this->firstFloatInRange($normalized, [
                '/fat\s*percentage[^\d]{0,24}(\d{1,3}(?:\.\d+)?)/i',
                '/fat[^\d]{0,10}%?[^\d]{0,10}(\d{1,3}(?:\.\d+)?)/i',
            ]),
            'muscle_mass_kg' => $this->firstFloatInRange($normalized, [
                '/muscle\s*mass[^\d]{0,24}(\d{1,3}(?:\.\d+)?)/i',
                '/muscle[^\d]{0,24}(\d{1,3}(?:\.\d+)?)[^\n]{0,8}kg/i',
            ]),
            'bmi' => $this->firstFloatInRange($normalized, [
                '/\bbmi\b[^\d]{0,10}(\d{1,3}(?:\.\d+)?)/i',
            ]),
            'visceral_fat_grade' => $this->firstIntInRange($normalized, [
                '/visceral\s*fat\s*grade[^\d]{0,18}(\d{1,3})/i',
                '/visceral[^\d]{0,18}(\d{1,3})/i',
            ]),
            'fat_mass_kg' => $this->firstFloatInRange($normalized, [
                '/fat\s*mass[^\d]{0,24}(\d{1,3}(?:\.\d+)?)/i',
                '/\bfat\s*mass\b[^\d]{0,24}(\d{1,3}(?:\.\d+)?)/i',
            ]),
            'lean_body_mass_kg' => $this->firstFloatInRange($normalized, [
                '/lean\s*body\s*mass[^\d]{0,24}(\d{1,3}(?:\.\d+)?)/i',
                '/lean[^\d]{0,24}(\d{1,3}(?:\.\d+)?)/i',
            ]),
            'evaluation_score' => $this->firstFloatInRange($normalized, [
                '/evaluation[^\d]{0,12}(\d{1,3}(?:\.\d+)?)/i',
                '/score[^\d]{0,10}(\d{1,3}(?:\.\d+)?)/i',
            ]),
            'physical_age' => $this->firstIntInRange($normalized, [
                '/physical\s*age[^\d]{0,10}(\d{1,3})/i',
            ]),
        ];
    }

    private function normalize(string $text): string
    {
        $text = str_replace(',', '.', $text);
        $text = preg_replace('/[^\S\r\n]+/', ' ', $text);

        return (string) $text;
    }

    private function firstFloatInRange(string $text, array $patterns): ?float
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                if (! isset($matches[1])) {
                    continue;
                }

                $value = (float) $matches[1];
                if ($value <= 0 || $value > 999.99) {
                    continue;
                }

                return $value;
            }
        }

        return null;
    }

    private function firstIntInRange(string $text, array $patterns): ?int
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                if (! isset($matches[1])) {
                    continue;
                }

                $value = (int) $matches[1];
                if ($value < 0 || $value > 999) {
                    continue;
                }

                return $value;
            }
        }

        return null;
    }
}
