<?php

use Carbon\Carbon;
use App\Users\Role;
use App\Users\User;
use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Venue;
use App\Users\Permission;
use App\AppEvents\Pricing;

/**
 * User Creation
 */
$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => str_random(10),
        'remember_token' => str_random(10),
        'status' => 'active'
    ];
});

$factory->define(Role::class, function (Faker\Generator $faker) {
    return [
        'name' => 'admin',
        'label' => 'Administrator'
    ];
});

$factory->define(Permission::class, function (Faker\Generator $faker) {
    return [
        'name' => 'access_admin_section',
        'label' => 'Can Access Administrative Section'
    ];
});

/**
 * Event Creation
 */
$factory->define(Event::class, function (Faker\Generator $faker) {
    return [
        'type' => 'conference',
        'name' => $faker->sentence(6),
        'description' => $faker->paragraph(3),
        'featured_image' => "http://lorempixel.com/1000/1000",
        'start_date' => Carbon::now()->addDays(7),
        'end_date' => Carbon::now()->addDays(9),
        'start_time' => Carbon::now()->addDays(7),
        'end_time' => Carbon::now()->addDays(7)->addHours(8),
        'next_date' => Carbon::now()->addDays(7),
        'published_at' => Carbon::now()->subDays(1)
    ];
});

/**
 * Venues Creation
 */
$factory->define(Venue::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(2),
        'address_line_one' => $faker->streetName,
        'address_line_two' => $faker->streetAddress,
        'city' => $faker->city,
        'province' => $faker->state,
        'country' => $faker->country,
        'area_code' => $faker->postcode
    ];
});

$factory->define(Date::class, function (Faker\Generator $faker) {
    return [
        'venue_id' => 1,
        'date' => Carbon::now()->addDays(7)
    ];
});

/**
 * Ticket Creation
 */
$factory->define(Pricing::class, function (Faker\Generator $faker) {
    return [
        'event_id' => 1,
        'venue_id' => 1,
        'name' => '1 Day Pass',
        'description' => '1 Day pass to event',
        'day_count' => 1,
        'price' => $faker->randomFloat(2, $min = 100, $max = 3500),
    ];
});