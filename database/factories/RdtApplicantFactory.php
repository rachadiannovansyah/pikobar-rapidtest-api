<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\RdtApplicantStatus;
use App\RdtApplicant;
use Faker\Generator as Faker;

$factory->define(RdtApplicant::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\id_ID\Person($faker));

    return [
        'nik'    => $faker->nik,
        'name'   => $faker->name,
        'status' => RdtApplicantStatus::NEW(),
    ];
});
