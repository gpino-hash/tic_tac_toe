<?php

use Faker\Generator as Faker;

$factory->define(\App\Board::class, function (Faker $faker) {
    return [
        "board"  =>  [0,0,0,0,0,0,0,0,0],
        "name" => $faker->name,
        "next" => $faker->numberBetween(1,2),
        "winner" => 0
    ];
});
