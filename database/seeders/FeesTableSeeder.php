<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fees')->insert([
            ['department' => 'Computer Science', 'amount' => 5000.00],
            ['department' => 'Engineering', 'amount' => 7000.00],
            ['department' => 'Business Administration', 'amount' => 4500.00],
            ['department' => 'Arts', 'amount' => 2500.00],
            ['department' => 'Medicine and Health', 'amount' => 12000.00],
            ['department' => 'Law', 'amount' => 9000.00],
            
        ]);
    }
}
