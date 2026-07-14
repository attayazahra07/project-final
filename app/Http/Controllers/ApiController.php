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

    public function getRisk(Request $request, RiskEngine $engine, \App\Services\ExternalApiService $api)
    {
        $countryCode = $request->query('country', 'ID');
        
        $country = DB::table('countries')->where('code', $countryCode)->first();
        if (!$country) {
            return response()->json(['error' => 'Country not found'], 404);
        }

        // Fetch Real Weather
        $weatherData = $api->getWeather($country->lat, $country->lng);
        // Translate weather code/temp to a risk score (0-100)
        // E.g., if windspeed > 30 it's risky, if temp very high/low it's risky
        $weatherRisk = 20; // Default Low
        if ($weatherData) {
            $wind = $weatherData['windspeed'] ?? 0;
            $weatherRisk = min(100, $wind * 2); // Simple heuristic: higher wind = higher risk
        }

        // Fetch Real Currency
        $rates = $api->getExchangeRates();
        $currencyRisk = 30; // Default
        $rateVal = null;
        if ($rates) {
            if (isset($rates[$country->currency_code])) {
                $rateVal = $rates[$country->currency_code];
            } else {
                $hash = abs(crc32($country->currency_code));
                $rateVal = ($hash % 100) + 1.25; // Stable mock rate based on currency code hash
            }
            $currencyRisk = min(100, ($rateVal > 1000 ? 50 : 20)); // Arbitrary for demo
        }

        // News Sentiment 
        $newsArticles = $api->getNews($country->name);
        $newsRisk = 40; // Default
        if (!empty($newsArticles)) {
            $analyzer = new SentimentAnalyzer();
            $texts = collect($newsArticles)->pluck('description')->join(' ');
            $sentiment = $analyzer->analyze($texts);
            $newsRisk = $sentiment['negative']; // Directly use negative % as risk
        }
        
        $factors = [
            'weather' => $weatherRisk,
            'inflation' => rand(20, 70), // WorldBank not fully integrated yet, use dummy
            'news' => $newsRisk,
            'currency' => $currencyRisk,
        ];
        
        $riskData = $engine->calculateScore($factors);
        
        // Save to DB
        DB::table('risk_scores')->updateOrInsert(
            ['country_id' => $country->id],
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
        
        $portsCount = DB::table('ports')->where('country_id', $country->id)->count();

        return response()->json([
            'total_score' => $riskData['total_score'],
            'risk_label' => $riskData['risk_label'],
            'breakdown' => $riskData['breakdown'],
            'ports_count' => $portsCount,
            'raw_data' => [
                'weather' => $weatherData ? [
                    'temp' => $weatherData['temperature'] ?? null,
                    'windspeed' => $weatherData['windspeed'] ?? null,
                    'weathercode' => $weatherData['weathercode'] ?? null,
                ] : null,
                'currency' => [
                    'code' => $country->currency_code,
                    'rate' => $rateVal
                ]
            ]
        ]);
    }

    public function getNews(Request $request, SentimentAnalyzer $analyzer, \App\Services\ExternalApiService $api)
    {
        $countryCode = $request->query('country', 'ID');
        $country = DB::table('countries')->where('code', $countryCode)->first();
        
        $articles = $api->getNews($country->name ?? 'Indonesia');
        
        if (empty($articles)) {
            $articles = [[
                'title' => 'No recent news found',
                'description' => 'Unable to fetch latest news for this region.',
                'source' => ['name' => 'System']
            ]];
            $sentiment = ['positive' => 0, 'negative' => 0, 'neutral' => 100];
        } else {
            $texts = collect($articles)->pluck('description')->join(' ');
            $sentiment = $analyzer->analyze($texts);
        }
        
        return response()->json([
            'news' => $articles,
            'sentiment' => $sentiment
        ]);
    }

    public function getCurrency(\App\Services\ExternalApiService $api)
    {
        $rates = $api->getExchangeRates();
        if (!$rates) {
            // Fallback
            return response()->json([
                'base' => 'USD',
                'rates' => [
                    'IDR' => 15000 + rand(-100, 100),
                    'EUR' => 0.92,
                    'GBP' => 0.79,
                    'JPY' => 145,
                ],
                'timestamp' => now()->toIso8601String()
            ]);
        }

        return response()->json([
            'base' => 'USD',
            'rates' => [
                'IDR' => $rates['IDR'] ?? null,
                'EUR' => $rates['EUR'] ?? null,
                'GBP' => $rates['GBP'] ?? null,
                'JPY' => $rates['JPY'] ?? null,
                'CNY' => $rates['CNY'] ?? null,
            ],
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
