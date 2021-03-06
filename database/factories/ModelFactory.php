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
        'category_id' => function () {
            return factory(App\Models\PlaceCategory::class)->create()->id;
        },
        'name' => $faker->company,
        'description' => $faker->paragraphs(5, true),
        'short_description' => $faker->paragraphs(1, true),
        'location' => [
            'city' => $faker->city,
            'street' => $faker->streetName,
            'zip' => $faker->postcode
        ],
        'rating' => $faker->randomFloat(null, 0, 5.0),
        'phone' => $faker->phoneNumber,
        'website' => $faker->url
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Event::class, function (Faker\Generator $faker) {
    return [
        'category_id' => null,
        'facebook_id' => $faker->randomNumber(5),
        'place_id' => function () {
            return factory(\App\Models\Place::class)->create()->id;
        },
        'name' => $faker->words(3, true),
        'description' => $faker->paragraphs(5, true),
        'end_time' => $faker->dateTime,
        'start_time' => $faker->dateTime
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->state(App\Models\Event::class, 'bare', function (Faker\Generator $faker) {
    return [
        'category_id' => null,
        'place_id' => null
    ];
});
