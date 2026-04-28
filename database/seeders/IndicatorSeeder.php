<?php

namespace Database\Seeders;

use App\Models\Indicator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndicatorSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Indicator::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $indicators = [
            ['code' => 'GDP',      'name_en' => 'Gross Domestic Product',   'unit' => 'USD billion', 'category' => 'Economy'],
            ['code' => 'GDP_PC',   'name_en' => 'GDP Per Capita',           'unit' => 'USD',         'category' => 'Economy'],
            ['code' => 'INF',      'name_en' => 'Inflation Rate',           'unit' => 'percent',     'category' => 'Economy'],
            ['code' => 'POP',      'name_en' => 'Total Population',         'unit' => 'millions',    'category' => 'Demographics'],
            ['code' => 'POP_URB',  'name_en' => 'Urban Population',         'unit' => 'percent',     'category' => 'Demographics'],
            ['code' => 'UNEMP',    'name_en' => 'Unemployment Rate',        'unit' => 'percent',     'category' => 'Labour'],
            ['code' => 'POVERTY',  'name_en' => 'Poverty Headcount Ratio',  'unit' => 'percent',     'category' => 'Social'],
            ['code' => 'LIFE_EXP', 'name_en' => 'Life Expectancy at Birth', 'unit' => 'years',       'category' => 'Health'],
            ['code' => 'CO2',      'name_en' => 'CO2 Emissions Per Capita', 'unit' => 'metric tons', 'category' => 'Environment'],
            ['code' => 'INTERNET', 'name_en' => 'Internet Users',           'unit' => 'percent',     'category' => 'Technology'],
        ];

        foreach ($indicators as $indicator) {
            Indicator::create($indicator);
        }
    }
}