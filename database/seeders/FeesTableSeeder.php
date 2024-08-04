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
        $fees = [
            'CS' => 5000.00,
            'ME' => 7000.00,
            'BA' => 4500.00,
            'ART' => 2500.00,
            'MH' => 12000.00,
            'LAW' => 9000.00,
        ];


        foreach ($fees as $code => $amount) {
            $department = Department::where('code', $code)->first();
            if ($department) {
                Log::info("Inserting fee for department: $code, amount: $amount, department_id: {$department->id}");
                DB::table('fees')->updateOrInsert([
                    'department_id' => $department->id,
                ], [
                    'department' => $department->name,
                    'amount' => $amount,
                ]);
            }else {
                Log::error("Department not found for code: $code");
            }
        }
    }
}
