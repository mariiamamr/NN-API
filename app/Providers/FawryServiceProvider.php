<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FawryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Container\Contracts\Payments\FawryContract', 'App\Container\Contracts\Payments\FawryEloquent');
    }

}
