<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['country_code' => 'PHL', 'name' => 'Philippines',  'level' => 'national'],
            ['country_code' => 'IDN', 'name' => 'Indonesia',    'level' => 'national'],
            ['country_code' => 'THA', 'name' => 'Thailand',     'level' => 'national'],
            ['country_code' => 'VNM', 'name' => 'Viet Nam',     'level' => 'national'],
            ['country_code' => 'MYS', 'name' => 'Malaysia',     'level' => 'national'],
            ['country_code' => 'SGP', 'name' => 'Singapore',    'level' => 'national'],
            ['country_code' => 'BGD', 'name' => 'Bangladesh',   'level' => 'national'],
            ['country_code' => 'PAK', 'name' => 'Pakistan',     'level' => 'national'],
            ['country_code' => 'IND', 'name' => 'India',        'level' => 'national'],
            ['country_code' => 'CHN', 'name' => 'China',        'level' => 'national'],
        ];

        foreach ($countries as $country) {
            Region::create($country);
        }

        // Sub-national: Philippine regions
        $philippines = Region::where('country_code', 'PHL')->first();

        $subNational = [
            'NCR - National Capital Region',
            'Region III - Central Luzon',
            'Region IV-A - CALABARZON',
            'Region VII - Central Visayas',
            'Region XI - Davao Region',
        ];

        foreach ($subNational as $name) {
            Region::create([
                'country_code' => 'PHL',
                'name'         => $name,
                'level'        => 'sub-national',
                'parent_id'    => $philippines->id,
            ]);
        }
    }
}