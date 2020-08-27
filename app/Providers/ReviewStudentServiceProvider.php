<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ReviewStudentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Container\Contracts\Reviews\ReviewStudentContract', 'App\Container\Contracts\Reviews\ReviewStudentEloquent');
        }

//     /**
//      * Bootstrap services.
//      *
//      * @return void
//      */
//     public function boot()
//     {
//         //
//     }
// }
}