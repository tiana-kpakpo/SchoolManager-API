<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('acadamic_year_increase', function () {
    $student = User::whereNotNull('year_of_study')->get();

    foreach($student as $student){
        $student->year_of_study += 1;
        $student->save();
    }
    
})->purpose('Display an inspiring quote')->yearlyOn(9, 7, '00:00');;
