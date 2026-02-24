<?php

namespace App\Services;

use App\Models\Setting;

class GradeService
{
    public function thresholds(): array
    {
        $default = [
            ['grade' => 'A', 'min' => 80],
            ['grade' => 'B', 'min' => 70],
            ['grade' => 'C', 'min' => 60],
            ['grade' => 'D', 'min' => 50],
            ['grade' => 'F', 'min' => 0],
        ];

        $row = Setting::query()->where('key', 'grade_thresholds')->first();
        if (! $row?->value) {
            return $default;
        }

        $decoded = json_decode($row->value, true);
        return is_array($decoded) ? $decoded : $default;
    }

    public function calculate(float $marks, float $totalMarks, float $passMarks): array
    {
        $percentage = $totalMarks > 0 ? round(($marks / $totalMarks) * 100, 2) : 0;
        $grade = 'F';

        foreach ($this->thresholds() as $threshold) {
            if ($percentage >= (float) ($threshold['min'] ?? 0)) {
                $grade = (string) ($threshold['grade'] ?? 'F');
                break;
            }
        }

        return [
            'percentage' => $percentage,
            'grade' => $grade,
            'pass_fail' => $marks >= $passMarks,
        ];
    }
}
