<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\RdtApplicant;
use App\Enums\Gender;
use App\Enums\PersonCaseStatusEnum;
use App\Enums\RdtApplicantStatus;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(RdtApplicant::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\id_ID\Person($faker));

    return [
        'nik'                  => $faker->nik,
        'name'                 => $faker->name,
        'gender'               => Arr::random(Gender::toArray()),
        'person_status'        => Arr::random(PersonCaseStatusEnum::toArray()),
        'birth_date'           => $faker->dateTimeThisCentury,
        'address'              => $faker->address,
        'email'                => $faker->email,
        'phone_number'         => $faker->phoneNumber,
        'symptoms_interaction' => Arr::random([0, 1, 2]),
        'symptoms_notes'       => $faker->text,
        'status'               => RdtApplicantStatus::NEW(),
    ];
});
