<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        $csvFile = base_path('World_Port_Index.csv');
        
        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found at: {$csvFile}");
            return;
        }

        $countries = DB::table('countries')->pluck('id', 'code')->toArray();
        // $countries will be like ['ID' => 1, 'US' => 2, ...]

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file);

        $portsToInsert = [];
        $count = 0;

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) != count($header)) continue;
            
            $data = array_combine($header, $row);
            
            $countryCode = $data['COUNTRY'] ?? null;
            $portName = $data['PORT_NAME'] ?? null;
            $lat = $data['LATITUDE'] ?? null;
            $lng = $data['LONGITUDE'] ?? null;
            $size = $data['HARBORSIZE'] ?? null;

            // Only insert ports for countries we have seeded
            if (isset($countries[$countryCode]) && $portName && $lat !== null && $lng !== null) {
                $portsToInsert[] = [
                    'country_id' => $countries[$countryCode],
                    'port_name' => mb_convert_encoding($portName, 'UTF-8', 'auto'),
                    'lat' => $lat,
                    'lng' => $lng,
                    'harbor_size' => $size ?: null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $count++;
            }

            // Insert in chunks to save memory
            if (count($portsToInsert) >= 500) {
                DB::table('ports')->insert($portsToInsert);
                $portsToInsert = [];
            }
        }

        if (!empty($portsToInsert)) {
            DB::table('ports')->insert($portsToInsert);
        }

        fclose($file);
        $this->command->info("Successfully imported {$count} ports from CSV.");
    }
}
