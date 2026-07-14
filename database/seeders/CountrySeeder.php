<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('seeders/countries.json');
        
        if (!file_exists($jsonPath)) {
            $this->command->error("Local countries.json not found!");
            return;
        }

        $rawCountries = json_decode(file_get_contents($jsonPath), true);
        if (!is_array($rawCountries)) {
            $this->command->error("Invalid countries.json content!");
            return;
        }

        $countries = [];
        foreach ($rawCountries as $c) {
            $code = $c['cca2'] ?? '';
            if (!$code || strlen($code) > 3) continue;

            // Extract currencies
            $currencyCode = '';
            $currencyName = '';
            if (!empty($c['currencies'])) {
                $currKeys = array_keys($c['currencies']);
                $currencyCode = $currKeys[0] ?? '';
                $currencyName = $c['currencies'][$currencyCode]['name'] ?? '';
            }

            // Extract location
            $lat = $c['latlng'][0] ?? null;
            $lng = $c['latlng'][1] ?? null;

            // Extract region/subregion
            $region = $c['subregion'] ?? $c['region'] ?? 'Unknown';

            $countries[] = [
                'code' => $code,
                'name' => $c['name']['common'] ?? $c['name']['official'] ?? 'Unknown',
                'region' => $region,
                'currency_code' => substr($currencyCode, 0, 10),
                'currency_name' => $currencyName,
                'lat' => $lat,
                'lng' => $lng,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // De-duplicate country codes if any
        $uniqueCountries = [];
        $seenCodes = [];
        foreach ($countries as $country) {
            if (!in_array($country['code'], $seenCodes)) {
                $seenCodes[] = $country['code'];
                $uniqueCountries[] = $country;
            }
        }

        // Insert in chunks of 50
        foreach (array_chunk($uniqueCountries, 50) as $chunk) {
            DB::table('countries')->insert($chunk);
        }

        $this->command->info("Successfully seeded " . count($uniqueCountries) . " countries.");
    }
}
