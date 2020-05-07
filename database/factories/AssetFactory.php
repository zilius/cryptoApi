<?php
declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Asset;
use Faker\Generator as Faker;

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

$factory->define(Asset::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1,51),
        'label' => $faker->randomElement(['USB','WALLET','CASH','FRIEND','EXTERNALHDD']),
        'currency_code' => $faker->randomElement(['BTC', 'EUR', 'IOTA', 'ETH', 'XRP', 'LTL']),
        'value' => $faker->randomFloat(3,1,100000),
    ];
});
