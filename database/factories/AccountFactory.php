<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Account;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'bank_id' => rand(1,4),
        'currency_id' => rand(1,4),
        'branch' => $faker->company,
        'account_number' => $faker->unique()->bankAccountNumber,
        'account_type' => ['Current','Saving','Credit','Joint'][rand(0,3)],
        'active' => rand(0,1)
    ];
});
