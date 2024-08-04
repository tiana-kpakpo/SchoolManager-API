<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('update:year-of-study', function () {
    
    DB::table('users')
        ->whereNotNull('student_id')
        ->where('year_of_study', '<', 4)
        ->increment('year_of_study');
    
    Log::info('Year of study updated successfully.');
})->purpose('Increment the year of study for all students')->yearlyOn(9, 7, '00:00');


