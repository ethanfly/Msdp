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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Admin::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'name' => 'admin',
        'password' => $password ? bcrypt($password) : $password = bcrypt('admin'),
        'remember_token' => str_random(10),
    ];
});
