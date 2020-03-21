<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Banner::class, function (Faker $faker) {
    return [
        'image_url'=>$faker->imageUrl(),
        'sort_num'=>rand(1,10),
        'on_sale'=>true
    ];
});
