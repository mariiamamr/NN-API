<?php

use Faker\Generator as Faker;
use App\Grade;

$factory->define(Grade::class, function (Faker $faker) {
    return [
        //
        'title' => $faker->word,
        'slug' => $faker->slug,
    ];
});
