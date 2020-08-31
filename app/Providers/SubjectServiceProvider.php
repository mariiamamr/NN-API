<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SubjectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Container\Contracts\Subjects\SubjectsContract', 'App\Container\Contracts\Subjects\SubjectsEloquent');
    }

}
