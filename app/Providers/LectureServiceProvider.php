<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LectureServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Container\Contracts\Lectures\LecturesContract', 'App\Container\Contracts\Lectures\LecturesEloquent');
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
