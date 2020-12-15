<?php

/** @var Factory $factory */

use App\Address;
use App\DrivingSchool;
use App\LearningPlace;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(LearningPlace::class, function (Faker $faker) {
    return [
        'driving_school_id' => factory(DrivingSchool::class)->create()->id,
        'type' => array_rand(LearningPlace::getTypes()),
        'address_id' => factory(Address::class)->create()->id,
        'description' => $faker->text,
    ];
});
