<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PromoCodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Container\Contracts\PromoCodes\PromoCodesContract', 'App\Container\Contracts\PromoCodes\PromoCodesEloquent');
    }

}
