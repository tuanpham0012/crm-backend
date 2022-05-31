<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use App\Models\TypeCustomer;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'customer_code' => Str::random(8),
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'address' => $faker->address,
        'gender' => 'Nam',
        'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'zalo' => $faker->e164PhoneNumber,
        'type_of_customer_id' => TypeCustomer::query()->inRandomOrder()->value('id'),
        'user_id' => 1,
        'deleted' => 0,
        'created_at' => new DateTime,
        'updated_at' => new DateTime,
    ];
});
