<?php

namespace Database\Seeders;

use App\Models\DataPoint;
use App\Models\Indicator;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPointSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DataPoint::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $gdpData = [
            'PHL' => [2020 => 361.5,  2021 => 394.1,  2022 => 435.7,  2023 => 464.4],
            'IDN' => [2020 => 1058.4, 2021 => 1186.5, 2022 => 1319.1, 2023 => 1417.4],
            'THA' => [2020 => 499.7,  2021 => 505.9,  2022 => 495.3,  2023 => 512.2],
            'VNM' => [2020 => 271.2,  2021 => 292.7,  2022 => 408.8,  2023 => 430.0],
            'MYS' => [2020 => 336.3,  2021 => 373.0,  2022 => 407.0,  2023 => 430.6],
            'SGP' => [2020 => 340.0,  2021 => 396.9,  2022 => 466.8,  2023 => 501.4],
            'IND' => [2020 => 2671.6, 2021 => 3150.3, 2022 => 3389.7, 2023 => 3732.2],
            'CHN' => [2020 => 14688,  2021 => 17734,  2022 => 17963,  2023 => 17794],
        ];

        $popData = [
            'PHL' => [2020 => 109.6, 2021 => 111.0, 2022 => 112.5, 2023 => 113.9],
            'IDN' => [2020 => 273.5, 2021 => 275.8, 2022 => 278.8, 2023 => 281.2],
            'THA' => [2020 => 69.8,  2021 => 69.9,  2022 => 70.0,  2023 => 70.2],
            'VNM' => [2020 => 97.3,  2021 => 98.2,  2022 => 98.2,  2023 => 98.9],
            'MYS' => [2020 => 32.7,  2021 => 32.9,  2022 => 33.2,  2023 => 33.6],
            'SGP' => [2020 => 5.7,   2021 => 5.5,   2022 => 5.6,   2023 => 5.9],
            'IND' => [2020 => 1380,  2021 => 1393,  2022 => 1407,  2023 => 1428],
            'CHN' => [2020 => 1411,  2021 => 1412,  2022 => 1412,  2023 => 1409],
        ];

        $gdpIndicator = Indicator::where('code', 'GDP')->first();
        $popIndicator = Indicator::where('code', 'POP')->first();

        foreach ($gdpData as $countryCode => $years) {
            $region = Region::where('country_code', $countryCode)
                ->where('level', 'national')
                ->first();

            if (!$region) continue;

            foreach ($years as $year => $value) {
                DataPoint::create([
                    'indicator_id' => $gdpIndicator->id,
                    'region_id'    => $region->id,
                    'year'         => $year,
                    'value'        => $value,
                    'source'       => 'World Bank',
                    'status'       => 'validated',
                ]);
            }
        }

        foreach ($popData as $countryCode => $years) {
            $region = Region::where('country_code', $countryCode)
                ->where('level', 'national')
                ->first();

            if (!$region) continue;

            foreach ($years as $year => $value) {
                DataPoint::create([
                    'indicator_id' => $popIndicator->id,
                    'region_id'    => $region->id,
                    'year'         => $year,
                    'value'        => $value,
                    'source'       => 'UN Population Division',
                    'status'       => 'validated',
                ]);
            }
        }
    }
}