<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $fees = [
        //     'CS' => 5000.00,
        //     'ME' => 7000.00,
        //     'BA' => 4500.00,
        //     'ART' => 2500.00,
        //     'MH' => 12000.00,
        //     'LAW' => 9000.00,
        // ];

        $fees = [
            'CS' => [
                'Tuition Fee' => 5000.00,
                'Library Fee' => 500.00,
            ],
            'ME' => [
                'Tuition Fee' => 7000.00,
                'Library Fee' => 700.00,
            ],
            'BA' => [
                'Tuition Fee' => 4500.00,
                'Library Fee' => 450.00,
            ],
            'ART' => [
                'Tuition Fee' => 2500.00,
                'Library Fee' => 250.00,
            ],
            'MH' => [
                'Tuition Fee' => 12000.00,
                'Library Fee' => 1200.00,
            ],
            'LAW' => [
                'Tuition Fee' => 9000.00,
                'Library Fee' => 900.00,
            ],
        ];

        foreach ($fees as $code => $feeTypes) {
            $department = Department::where('code', $code)->first();
            if ($department) {
                foreach ($feeTypes as $name => $amount) {
                Log::info("Inserting fee for department: $code, amount: $amount, department_id: {$department->id}");
                DB::table('fees')->updateOrInsert([
                    'department_id' => $department->id,
                    'name' => $name
                ], [
                    'department' => $department->name,
                    'amount' => $amount,
                ]);
            }
        }else {
                Log::error("Department not found for code: $code");
            }
        }
    }
}
