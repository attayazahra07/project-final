<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SentimentAnalyzer
{
    public function analyze(string $text)
    {
        $text = strtolower($text);
        // Remove basic punctuation
        $text = preg_replace('/[^\w\s]/', '', $text);
        $words = explode(' ', $text);

        $positiveWords = Cache::remember('positive_words', 86400, function () {
            return DB::table('positive_words')->pluck('word')->toArray();
        });

        $negativeWords = Cache::remember('negative_words', 86400, function () {
            return DB::table('negative_words')->pluck('word')->toArray();
        });

        $posCount = 0;
        $negCount = 0;

        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) {
                $posCount++;
            } elseif (in_array($word, $negativeWords)) {
                $negCount++;
            }
        }

        $totalSentimentWords = $posCount + $negCount;
        
        if ($totalSentimentWords === 0) {
            return [
                'positive' => 0,
                'negative' => 0,
                'neutral' => 100,
            ];
        }

        $posPercentage = ($posCount / $totalSentimentWords) * 100;
        $negPercentage = ($negCount / $totalSentimentWords) * 100;

        // Simplify neutral as remaining logic based on density? 
        // For simple Lexicon, we can just say if it's very mixed, it leans neutral.
        // But for our dashboard, we just need Pos vs Neg ratio.
        
        return [
            'positive' => round($posPercentage, 2),
            'negative' => round($negPercentage, 2),
            'neutral' => 0, // We can calculate neutral based on non-sentiment words if needed
        ];
    }
}
