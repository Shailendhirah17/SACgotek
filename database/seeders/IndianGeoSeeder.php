<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class IndianGeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Truncate existing data to ensure a clean exhaustive re-seed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sm_cities')->truncate();
        DB::table('sm_states')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Load the exhaustive JSON data (750+ districts)
        $jsonPath = database_path('data/indian_districts.json');
        if (!File::exists($jsonPath)) {
            throw new \Exception("Geographic data file not found at: {$jsonPath}");
        }

        $content = File::get($jsonPath);
        $data = json_decode($content, true);

        if (!$data || !isset($data['districts'])) {
            throw new \Exception("Invalid JSON structure in {$jsonPath}");
        }

        $countryId = 101; // India
        $stateCache = [];

        // 3. Process the exhaustive list
        foreach ($data['districts'] as $item) {
            $stateName = $item['state'];
            $districtName = $item['district'];

            // Cache state lookups/inserts to avoid repeated queries
            if (!isset($stateCache[$stateName])) {
                $stateId = DB::table('sm_states')->insertGetId([
                    'name' => $stateName,
                    'code' => $item['stateCode'] ?? null,
                    'country_id' => $countryId,
                    'active_status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $stateCache[$stateName] = $stateId;
            }

            // Insert district
            DB::table('sm_cities')->insert([
                'name' => $districtName,
                'state_id' => $stateCache[$stateName],
                'active_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
