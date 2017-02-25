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
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\PlaceCategory::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Place::class, function (Faker\Generator $faker) {
    return [
        'category_id' => factory(App\Models\PlaceCategory::class)->create()->id,
        'name' => $faker->company,
        'description' => $faker->paragraphs(5, true),
        'short_description' => $faker->paragraphs(1, true),
        'location' => json_encode($faker->address),
        'rating' => $faker->randomFloat(),
        'phone' => $faker->phoneNumber,
        'website' => $faker->url
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Event::class, function (Faker\Generator $faker) {
    return [
        'category_id' => null,
        'place_id' => factory(\App\Models\Place::class)->create()->id,
        'name' => $faker->words(),
        'description' => $faker->paragraphs(5),
        'end_time' => $faker->dateTime,
        'start_time' => $faker->dateTime,
        'extra_info' => json_encode([])
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->state(App\Models\Event::class, 'bare', function (Faker\Generator $faker) {
    return [
        'category_id' => null,
        'place_id' => null
    ];
});
