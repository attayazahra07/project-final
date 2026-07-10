<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    /**
     * Get basic country info from REST Countries API
     */
    public function getCountryInfo(string $countryCode)
    {
        return Cache::remember("country_info_{$countryCode}", 86400, function () use ($countryCode) {
            try {
                $response = Http::timeout(5)->get("https://restcountries.com/v3.1/alpha/{$countryCode}");
                if ($response->successful()) {
                    return $response->json()[0] ?? null;
                }
            } catch (\Exception $e) {
                Log::error("REST Countries API Error: " . $e->getMessage());
            }
            return null;
        });
    }

    /**
     * Get current weather from Open-Meteo
     */
    public function getWeather(float $lat, float $lng)
    {
        $cacheKey = "weather_" . round($lat, 2) . "_" . round($lng, 2);
        
        return Cache::remember($cacheKey, 7200, function () use ($lat, $lng) { // Cache 2 hours
            try {
                $response = Http::timeout(5)->get("https://api.open-meteo.com/v1/forecast", [
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'current_weather' => 'true'
                ]);
                
                if ($response->successful()) {
                    return $response->json()['current_weather'] ?? null;
                }
            } catch (\Exception $e) {
                Log::error("Open-Meteo API Error: " . $e->getMessage());
            }
            return null;
        });
    }

    /**
     * Get latest exchange rates against USD
     */
    public function getExchangeRates()
    {
        return Cache::remember('exchange_rates_usd', 7200, function () { // Cache 2 hours
            try {
                $apiKey = env('EXCHANGERATE_API_KEY');
                if (!$apiKey) return null;

                $response = Http::timeout(5)->get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/USD");
                
                if ($response->successful()) {
                    return $response->json()['conversion_rates'] ?? null;
                }
            } catch (\Exception $e) {
                Log::error("ExchangeRate API Error: " . $e->getMessage());
            }
            return null;
        });
    }

    /**
     * Get latest news related to logistics/supply chain for a country
     */
    public function getNews(string $countryName)
    {
        $cacheKey = "news_" . strtolower(str_replace(' ', '_', $countryName));
        
        return Cache::remember($cacheKey, 21600, function () use ($countryName) { // Cache 6 hours
            try {
                $apiKey = env('GNEWS_API_KEY');
                if (!$apiKey) return null;

                $query = urlencode("supply chain OR logistics OR port \"{$countryName}\"");
                $response = Http::timeout(5)->get("https://gnews.io/api/v4/search?q={$query}&apikey={$apiKey}&max=3&lang=en");
                
                if ($response->successful()) {
                    return $response->json()['articles'] ?? [];
                }
            } catch (\Exception $e) {
                Log::error("GNews API Error: " . $e->getMessage());
            }
            return [];
        });
    }
}
