<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => \Illuminate\Support\Facades\Hash::make('123213123'),
        'remember_token' => str_random(10),
        "full_name" => $faker->name,
        "type" => $faker->randomElement(['s', 't']),
        "birth" => $faker->date('Y-m-d'),
        "gender" => $faker->randomElement(['male', 'female'])
    ];
});
