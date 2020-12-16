<?php

/** @var Factory $factory */

use App\Conclusion;
use App\ConclusionResult;
use App\LearningPlace;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(ConclusionResult::class, function (Faker $faker) {
    return [
        'conclusion_id' => factory(Conclusion::class)->create()->id,
        'learning_place_id' => factory(LearningPlace::class)->create()->id,
        'starts_at' => Carbon::now()->subMonth(),
        'ends_at' => Carbon::now()->addMonth(),
    ];
});
