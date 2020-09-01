<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UniversityDegreeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Container\Contracts\UniversityDegrees\UniversityDegreesContract', 'App\Container\Contracts\UniversityDegrees\UniversityDegreesEloquent');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
