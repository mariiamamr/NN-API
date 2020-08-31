<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SearchQueryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->app->bind('App\Container\Contracts\Search\SearchQueryContract', 'App\Container\Contracts\Search\SearchQueryEloquent');
    }

}
