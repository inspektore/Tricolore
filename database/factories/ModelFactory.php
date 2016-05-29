<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Tricolore\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Tricolore\Thread::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->text(50),
        'user_id' => mt_rand(1, 5),
        'forum_id' => mt_rand(1, 500),
        'flag' => 'open',
        'visitor' => $faker->ipv4
    ];
});
