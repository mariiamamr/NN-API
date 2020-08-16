<?php

use Faker\Generator as Faker;
use App\Subject;

$factory->define(Subject::class, function (Faker $faker) {
    return [
        //
        'title' => $faker->word,
        'slug' => $faker->slug,
    ];
});
