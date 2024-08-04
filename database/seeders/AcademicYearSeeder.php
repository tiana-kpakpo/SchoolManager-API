<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYears = [
            ['name' => '2023/2024'],
            ['name' => '2024/2025'],
        ];

        foreach ($academicYears as $academicYear) {
            DB::table('academic_years')->updateOrInsert(['name' => $academicYear['name']], $academicYear);
        }
    }
}
