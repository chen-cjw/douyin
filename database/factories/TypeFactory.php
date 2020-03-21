<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Type::class, function (Faker $faker) {
    return [
        'name_zh'=>$faker->name,
        'name_en'=> 'name_en',
        'image_url'=>$faker->imageUrl(),
        'sort_num'=>rand(1,100),
        'on_sale'=>true

    ];
});
