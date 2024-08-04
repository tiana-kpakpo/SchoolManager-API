<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemestersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $semesters = [
            ['name' => 'Spring', 'year' => 2024],
            ['name' => 'Fall', 'year' => 2024],
        ];

        foreach ($semesters as $semester) {
            Semester::updateOrInsert([
                'name' => $semester['name'],
                'year' => $semester['year'],
            ], $semester);
        }
    }
}
