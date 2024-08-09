<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = Department::firstOrCreate(['name' => 'Administration'], ['code' => 'ADMIN']);

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('adminpassword'), // Default password
            'is_admin' => true,
            'department' => $department->name,
            // 'code' => 'ADMIN'
            
        ]);
    }
}
