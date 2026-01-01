<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = include database_path('seeders/data/departments_list.php');

                                                //array of departments, array of unique, columns to update
        DB::table('departments')->upsert($departments, ['name'], ['slug', 'is_capital', 'extension', 'population', 'region', 'capital', 'code']);
    }
}
