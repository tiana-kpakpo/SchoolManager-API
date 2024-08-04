<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class IncrementYearOfStudy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:increment-year-of-study';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Increment the year of study for all students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       User::where('is_admin', false || 'lecturer_id' === null)->increment('year_of_study');

       $this->info('Year of study updated successfully');

    }
}
