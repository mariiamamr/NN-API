<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CalenderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Container\Contracts\Calenders\CalendersContract', 'App\Container\Contracts\Calenders\CalendersEloquent');
    }

    // /**
    //  * Bootstrap services.
    //  *
    //  * @return void
    //  */
    // public function boot()
    // {
    //     //
    // }
}
