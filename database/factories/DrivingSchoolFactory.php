<?php

/** @var Factory $factory */

use App\Address;
use App\DrivingSchool;
use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(DrivingSchool::class, function (Faker $faker) {
    return [
        'name' => $faker->boolean ? $faker->company : null,
        'legal_name' => $faker->company,
        'slug' => $faker->slug,
        'inn' => $faker->inn,
        'type' => array_rand(DrivingSchool::getTypes()),
        'post_status' => array_rand(DrivingSchool::getPostStatuses()),
        'school_status' => array_rand(DrivingSchool::getSchoolStatuses()),
        'description' => $faker->text,
        'logo' => null,
        'open_date' => Carbon::yesterday(),
        'close_date' => null,
        'author_id' => \factory(User::class)->create()->id,
        'address_id' => \factory(Address::class)->create()->id,
    ];
});
