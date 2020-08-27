<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserInfoReviewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Container\Contracts\UserInfos\UserInfoReviewsContract', 'App\Container\Contracts\UserInfos\UserInfoReviewsEloquent');
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
