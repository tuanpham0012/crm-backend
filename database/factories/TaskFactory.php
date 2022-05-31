<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\TypeOfTask;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'start' => $faker->dateTimeThisMonth($max = 'now', $timezone = null),
        'end' => Carbon::now()->addDay(rand(5, 10)),
        'content' => $faker->text($maxNbChars = 120),
        'type_of_task_id' => TypeOfTask::query()->inRandomOrder()->value('id'),
        'user_id' => User::query()->inRandomOrder()->value('id'),
        'customer_id' => null,
        'task_status_id' => TaskStatus::query()->inRandomOrder()->value('id'),
        'created_at' => new DateTime,
        'updated_at' => new DateTime,
    ];
});