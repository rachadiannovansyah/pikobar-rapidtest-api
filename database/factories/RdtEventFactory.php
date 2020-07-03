<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\RdtEventStatus;
use App\Entities\RdtEvent;
use Faker\Generator as Faker;

$factory->define(RdtEvent::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\id_ID\Address($faker));

    return [
        'event_location' => $faker->address,
        'status'         => RdtEventStatus::PUBLISHED(),
    ];
});
