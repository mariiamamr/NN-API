<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserInfoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Container\Contracts\UserInfos\WeeklyContract', 'App\Container\Contracts\UserInfos\WeeklyEloquent');
        $this->app->bind('App\Container\Contracts\UserInfos\UserInfosContract', 'App\Container\Contracts\UserInfos\UserInfosEloquent');
        $this->app->bind('App\Container\Contracts\Users\UserEnrollsContract', 'App\Container\Contracts\Users\UserEnrollsEloquent');

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
