<?php

/** @var Factory $factory */

use App\DrivingSchool;
use App\Program;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Program::class, function (Faker $faker) {
    return [
        'driving_school_id' => factory(DrivingSchool::class)->create()->id,
        'name' => $faker->sentence,
        'is_akpp' => $faker->boolean,
        'is_retraining' => $faker->boolean,
        'description' => $faker->text,
        'price' => $faker->randomFloat(2, 100, 999999),
        'price_type' => array_rand(Program::getPriceTypes()),
    ];
});
