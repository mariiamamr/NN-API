<?php

use Faker\Generator as Faker;
use App\Language;

$factory->define(Language::class, function (Faker $faker) {
    return [
        //
        'title' => $faker->languageCode,
        'slug' => $faker->languageCode,
    ];
});
