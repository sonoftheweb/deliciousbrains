<?php

/** @var Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Storage;

$factory->define(\App\Models\UserProfile::class, function (Faker $faker) {
    return [
        'avatar' => Storage::disk('uploads')->url('avatar.png')
    ];
});
