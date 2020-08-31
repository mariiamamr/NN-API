<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->app->bind('App\Container\Contracts\Languages\LanguagesContract', 'App\Container\Contracts\Languages\LanguagesEloquent');
    }

}
