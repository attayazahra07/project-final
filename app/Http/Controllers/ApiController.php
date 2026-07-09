<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\SentimentAnalyzer;
use App\Services\RiskEngine;

class ApiController extends Controller
{
    public function getCountries()
    {
        $countries = DB::table('countries')->get();
        return response()->json($countries);
    }

    public function getPorts(Request $request)
    {
        $countryCode = $request->query('country');
        
        $query = DB::table('ports')
            ->join('countries', 'ports.country_id', '=', 'countries.id')
            ->select('ports.*', 'countries.name as country_name', 'countries.code as country_code');
            
        if ($countryCode) {
            $query->where('countries.code', $countryCode);
        }
        
        // Limit to 500 ports to prevent heavy map load in this phase
        return response()->json($query->limit(500)->get());
    }

    public function getRisk(Request $request, RiskEngine $engine)
    {
        // Mock data for Phase 3 (Will connect to external API in Phase 4)
        $country_id = $request->query('country_id', 1); // Default ID
        
        // Generate pseudo-random factors based on country ID for testing
        $factors = [
            'weather' => rand(10, 80),
            'inflation' => rand(20, 70),
            'news' => rand(30, 90),
            'currency' => rand(10, 50),
        ];
        
        $riskData = $engine->calculateScore($factors);
        
        // Save to DB (mocking the persistence)
        DB::table('risk_scores')->updateOrInsert(
            ['country_id' => $country_id],
            [
                'weather_risk' => $factors['weather'],
                'inflation_risk' => $factors['inflation'],
                'currency_risk' => $factors['currency'],
                'news_risk' => $factors['news'],
                'total_risk' => $riskData['total_score'],
                'risk_label' => $riskData['risk_label'],
                'updated_at' => now(),
            ]
        );
        
        return response()->json($riskData);
    }

    public function getNews(Request $request, SentimentAnalyzer $analyzer)
    {
        // Mock news data
        $sampleText = "The economic growth is stable and positive, despite some minor delay and risk in shipping.";
        
        $sentiment = $analyzer->analyze($sampleText);
        
        return response()->json([
            'news' => [
                'title' => 'Economic Update',
                'description' => $sampleText,
                'source' => 'Global Finance',
            ],
            'sentiment' => $sentiment
        ]);
    }

    public function getCurrency()
    {
        // Mock Currency Data
        return response()->json([
            'base' => 'USD',
            'rates' => [
                'IDR' => 15000 + rand(-100, 100),
                'EUR' => 0.92 + (rand(-10, 10) / 1000),
                'GBP' => 0.79 + (rand(-10, 10) / 1000),
                'JPY' => 145 + rand(-5, 5),
            ],
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
