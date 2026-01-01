<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = include database_path('seeders/data/cities_list.php');

        DB::table('cities')->upsert($cities, ['department_id', 'name'], ['is_capital', 'population']);
    }
}
