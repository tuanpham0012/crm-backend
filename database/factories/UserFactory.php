<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Models\Role;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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

$factory->define(User::class, function (Faker $faker) {
    return [
        'employee_code' => Str::orderedUuid(),
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'phone' => $faker->phoneNumber,
        'date_of_birth' => '',
        'gender' => 'male',
        'status' => '',
        'password' => Hash::make('admin123'),
        'role_id' => Role::query()->inRandomOrder()->value('id'),
        'created_at' => new DateTime,
        'updated_at' => new DateTime,
        'remember_token' => Str::random(10),
    ];
});
