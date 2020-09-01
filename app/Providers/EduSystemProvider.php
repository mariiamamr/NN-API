<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EduSystemProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Container\Contracts\EduSystems\EduSystemsContract', 'App\Container\Contracts\EduSystems\EduSystemsEloquent');
    }
}
