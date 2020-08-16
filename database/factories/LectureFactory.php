<?php

use Faker\Generator as Faker;

$factory->define(\App\Lecture::class, function (Faker $faker) {
    return [
        "checkout_user_id" => null,
        "started" => false,
        "payed_user_id" => null,
        "completed_at" => null,
        "teacher_id" => $faker->randomDigitNotNull,
        "date" => $faker->date('Y-m-d', 'now'),
        "time_to" => $et = $faker->time(),
        "time_from" => $st_time = $faker->time('H:i:s', $et),
    ];
});
