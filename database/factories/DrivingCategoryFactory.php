<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DrivingCategory;
use Faker\Generator as Faker;

$factory->define(DrivingCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->unique->randomLetter,
        'short_description' => $faker->sentence,
        'description' => $faker->text,
    ];
});
