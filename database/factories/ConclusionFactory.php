<?php

/** @var Factory $factory */

use App\Conclusion;
use App\DrivingSchool;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Conclusion::class, function (Faker $faker) {
    return [
        'number' => (string)$faker->unique()->randomNumber(5),
        'driving_school_id' => factory(DrivingSchool::class)->create()->id,
        'verified_by_user_id' => factory(User::class)->create()->id,
    ];
});
