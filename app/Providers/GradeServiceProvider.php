<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;



class GradeServiceProvider extends ServiceProvider {
   public function register() {
        $this->app->bind('App\Container\Contracts\Grades\GradesContract', 'App\Container\Contracts\Grades\GradesEloquent');
        //$this->app->bind('App\Container\Contracts\Lectures\LecturesContract', 'App\Container\Contracts\Lectures\LecturesEloquent');
    }

}

// class GradeServiceProvider extends ServiceProvider
// {
//     /**
//      * Register services.
//      *
//      * @return void
//      */
//     public function register()
//     {
//         //
//     }

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
