<?php

use Faker\Generator as Faker;
use App\UserInfo;

$factory->define(UserInfo::class, function (Faker $faker) {
    return [
        "user_id" => 1,
        "nationality" => $faker->country,
        "phone" => $faker->phoneNumber,
        "postal_code" => $faker->postcode,
        "exp_years" => $faker->randomDigitNotNull,
        "exp_desc" => $faker->text,
        "payment_info" => json_encode($faker->creditCardDetails),
        "avg_rate" => $faker->randomFloat(1, 1, 5),
        "month_rate" => $faker->randomFloat(1, 1, 5),
        "rank" => $faker->randomDigitNotNull,
        "rates_count" => $faker->randomNumber(2),
        "courses" => json_encode($faker->sentences(3)),
        "certifications" => json_encode($faker->sentences(3)),
        "master_degree" => $faker->word,
        "weekly" => json_encode($faker->randomElements(["Sun", "Mon", "Tue", "Wed", "Thus", "Fri", "Sat"], 2))
    ];
});
