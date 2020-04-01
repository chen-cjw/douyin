<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {
    return [
        'name' => 'https://goss.veer.com/creative/vcg/veer/800water/veer-304393485.jpg',
        'sort_num' => rand(1,100),
        'on_sale'=> true

    ];
});
