<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemestersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('semesters')->insert([
            ['name' => 'Fall 2024', 'year' => 2024],
            ['name' => 'Spring 2024', 'year' => 2024],
            ['name' => 'Summer 2024', 'year' => 2024],
         
        ]);
    }
}
