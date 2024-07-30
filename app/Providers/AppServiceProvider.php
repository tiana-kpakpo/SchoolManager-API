<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\User;
use App\Policies\CoursePolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    protected $policies = [
        Course::class => CoursePolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['Illuminate\Contracts\Auth\Access\Gate']->policy(Course::class, CoursePolicy::class);

    }
}
