<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Hospital;
use Faker\Generator as Faker;

$factory->define(Hospital::class, function (Faker $faker) {
    return [
        "name" => $faker->name,
        "address" => $faker->address,
        "description" => $faker->text,
        "phone_numbers" => $faker->phoneNumber,
    ];
});
