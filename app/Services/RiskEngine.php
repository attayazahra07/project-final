<?php

namespace App\Services;

class RiskEngine
{
    /**
     * Calculates combined risk score based on defined weights
     * Weather: 30%, Inflation: 20%, News: 40%, Currency: 10%
     */
    public function calculateScore(array $factors): array
    {
        // Default values to 0 if not provided
        $weather = $factors['weather'] ?? 0;
        $inflation = $factors['inflation'] ?? 0;
        $news = $factors['news'] ?? 0;
        $currency = $factors['currency'] ?? 0;

        // Ensure values are within 0-100 range
        $weather = max(0, min(100, $weather));
        $inflation = max(0, min(100, $inflation));
        $news = max(0, min(100, $news));
        $currency = max(0, min(100, $currency));

        // Calculate weighted score
        $totalScore = ($weather * 0.30) + ($inflation * 0.20) + ($news * 0.40) + ($currency * 0.10);

        // Determine Risk Label
        if ($totalScore <= 35) {
            $label = 'Low';
        } elseif ($totalScore <= 65) {
            $label = 'Medium';
        } else {
            $label = 'High';
        }

        return [
            'total_score' => round($totalScore, 2),
            'risk_label' => $label,
            'breakdown' => [
                'weather' => $weather,
                'inflation' => $inflation,
                'news' => $news,
                'currency' => $currency
            ]
        ];
    }
}
