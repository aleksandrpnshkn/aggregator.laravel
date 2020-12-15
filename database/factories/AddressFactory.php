<?php

/** @var Factory $factory */

use App\Address;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'value' => $faker->address,
        'region_with_type' => $faker->state,
        'city_with_type' => $faker->city,
        'street_with_type' => $faker->streetName,
        'house' => $faker->randomNumber(2),
        'geo_lat' => $faker->latitude,
        'geo_lon' => $faker->longitude,
    ];
});
