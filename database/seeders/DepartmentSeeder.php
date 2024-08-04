<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $departments = [
            ['name' => 'Computer Science', 'code' => 'CS'],
            ['name' => 'Mechanical Engineering', 'code' => 'ME'],
            ['name' => 'Business Administration', 'code' => 'BA'],
            ['name' => 'Arts', 'code' => 'ART'],
            ['name' => 'Medicine and Health', 'code' => 'MH'],
            ['name' => 'Law', 'code' => 'LAW'],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(['code' => $department['code']], $department);
        }
    }

}
