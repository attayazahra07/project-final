<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WordSeeder extends Seeder
{
    public function run(): void
    {
        $positive = [
            'growth', 'recovery', 'stable', 'boom', 'profit', 'surplus',
            'increase', 'strong', 'positive', 'success', 'expand', 'efficient',
            'safe', 'secure', 'smooth', 'optimistic', 'benefit', 'improve',
            'up', 'gain', 'advantage', 'progress', 'boost', 'clear', 'good',
            'thrive', 'thriving', 'opportunity', 'prosper', 'resolve'
        ];

        $negative = [
            'risk', 'delay', 'crisis', 'drop', 'loss', 'deficit',
            'decrease', 'weak', 'negative', 'fail', 'shrink', 'inefficient',
            'danger', 'insecure', 'disrupt', 'pessimistic', 'harm', 'decline',
            'down', 'hurt', 'disadvantage', 'regress', 'crash', 'bad', 'poor',
            'suffer', 'struggle', 'threat', 'issue', 'problem', 'ban', 'strike',
            'war', 'tension', 'storm', 'hurricane', 'typhoon', 'flood', 'earthquake'
        ];

        $posData = array_map(fn($w) => ['word' => $w, 'created_at' => now(), 'updated_at' => now()], $positive);
        $negData = array_map(fn($w) => ['word' => $w, 'created_at' => now(), 'updated_at' => now()], $negative);

        DB::table('positive_words')->insert($posData);
        DB::table('negative_words')->insert($negData);
    }
}
