<?php

use App\Models\InstagramUser;
use Faker\Generator as Faker;

$factory->define(InstagramUser::class, function (Faker $faker) {
    return [
        'handle' => $faker->name,
    ];
});
