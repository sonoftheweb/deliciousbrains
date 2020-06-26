<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(\App\Models\AccountActivity::class, function (Faker $faker) {
    return [
        'amount' => $faker->randomFloat(2, -100, 550), // in dollars
        'description' => $faker->randomElement(['Groceries', 'Lottery Win', 'Car Insurance', 'Rent', 'Utility Bill']),
        'activity_date' => $faker->dateTimeInInterval('-5 days', 'now')
    ];
});
