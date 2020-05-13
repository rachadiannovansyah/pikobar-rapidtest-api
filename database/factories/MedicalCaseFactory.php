<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Area;
use App\User;
use App\MedicalCase;
use Faker\Generator as Faker;

$factory->define(MedicalCase::class, function (Faker $faker) {
    $user = $faker->randomElement(User::all());
    $birth_date = $faker->dateTimeBetween($startDate = '-70 years', $endDate = 'now');

    return [
      'nik' => sprintf('%d%d', $faker->randomNumber(8), $faker->randomNumber(8) ),
      'name' => $faker->name,
      'birth_date' => $birth_date->format('Y-m-d'),
      'age' => (new \DateTime('now'))->diff($birth_date)->format('%y'),

      "gender" => $faker->randomElement([MedicalCase::MALE_GENDER, MedicalCase::FEMALE_GENDER ]),
      "phone_number" => sprintf('08%d', $faker->randomNumber(9)),
      "address" => $faker->streetAddress,

      "office_address" => $faker->streetAddress,
      'occupation_id' => $faker->randomDigitNotNull,
      'nationality' => MedicalCase::WNI,
      'author_id' => $user->id,

    ];
});
