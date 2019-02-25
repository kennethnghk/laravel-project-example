<?php

use Faker\Generator as Faker;
use App\Core\Models\Merchant;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Merchant::class, function (Faker $faker) {
    return [
        Merchant::COLUMN_NAME => $faker->name,
        Merchant::COLUMN_EMAIL => $faker->unique()->safeEmail,
        Merchant::COLUMN_LOCATION => $faker->longitude.','.$faker->latitude,
        Merchant::COLUMN_CREATED_AT => now(),
        Merchant::COLUMN_UPDATED_AT => now()
    ];
});
