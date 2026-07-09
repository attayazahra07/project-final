<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['code' => 'ID', 'name' => 'Indonesia', 'region' => 'Southeast Asia', 'currency_code' => 'IDR', 'currency_name' => 'Indonesian Rupiah', 'lat' => -0.789275, 'lng' => 113.921327],
            ['code' => 'US', 'name' => 'United States', 'region' => 'North America', 'currency_code' => 'USD', 'currency_name' => 'US Dollar', 'lat' => 37.090240, 'lng' => -95.712891],
            ['code' => 'CN', 'name' => 'China', 'region' => 'East Asia', 'currency_code' => 'CNY', 'currency_name' => 'Chinese Yuan', 'lat' => 35.861660, 'lng' => 104.195397],
            ['code' => 'JP', 'name' => 'Japan', 'region' => 'East Asia', 'currency_code' => 'JPY', 'currency_name' => 'Japanese Yen', 'lat' => 36.204824, 'lng' => 138.252924],
            ['code' => 'DE', 'name' => 'Germany', 'region' => 'Europe', 'currency_code' => 'EUR', 'currency_name' => 'Euro', 'lat' => 51.165691, 'lng' => 10.451526],
            ['code' => 'GB', 'name' => 'United Kingdom', 'region' => 'Europe', 'currency_code' => 'GBP', 'currency_name' => 'British Pound', 'lat' => 55.378051, 'lng' => -3.435973],
            ['code' => 'IN', 'name' => 'India', 'region' => 'South Asia', 'currency_code' => 'INR', 'currency_name' => 'Indian Rupee', 'lat' => 20.593684, 'lng' => 78.962880],
            ['code' => 'BR', 'name' => 'Brazil', 'region' => 'South America', 'currency_code' => 'BRL', 'currency_name' => 'Brazilian Real', 'lat' => -14.235004, 'lng' => -51.925280],
            ['code' => 'SG', 'name' => 'Singapore', 'region' => 'Southeast Asia', 'currency_code' => 'SGD', 'currency_name' => 'Singapore Dollar', 'lat' => 1.352083, 'lng' => 103.819836],
            ['code' => 'AE', 'name' => 'United Arab Emirates', 'region' => 'Middle East', 'currency_code' => 'AED', 'currency_name' => 'UAE Dirham', 'lat' => 23.424076, 'lng' => 53.847818],
        ];

        $data = array_map(function ($c) {
            $c['created_at'] = now();
            $c['updated_at'] = now();
            return $c;
        }, $countries);

        DB::table('countries')->insert($data);
    }
}
