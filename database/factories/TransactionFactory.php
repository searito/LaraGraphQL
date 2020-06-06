<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(App\Transaction::class, function (Faker $faker) {
    return [
        'account_id' => factory(App\Account::class),
        'category_id' => $faker->randomNumber(),
        'amount' => $faker->randomFloat(),
        'type' => $faker->randomElement(['INCOME', 'EXPENSE']),
        'description' => $faker->text,
    ];
});

$factory->state(App\Transaction::class, 'income', function (Faker $faker) {
    return [
        'account_id'    => factory(App\Account::class),
        'amount'        => $faker->randomFloat(),
        'type'          => 'INCOME',
        'description'   => $faker->text,
    ];
});

$factory->state(App\Transaction::class, 'expense', function (Faker $faker) {
    return [
        'account_id'    => factory(App\Account::class),
        'amount'        => $faker->randomFloat(),
        'type'          => 'EXPENSE',
        'description'   => $faker->text,
    ];
});
