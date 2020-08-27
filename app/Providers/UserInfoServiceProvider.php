<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Container\Contracts\Users\UserEnrollsContract;
use App\Container\Contracts\Users\UserEnrollsEloquent;


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

        //$this->app->bind(UserEnrollsContract::class, UserEnrollsEloquent::class);

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
