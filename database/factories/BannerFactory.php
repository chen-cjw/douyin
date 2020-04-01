<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Banner::class, function (Faker $faker) {
    return [
        'image_url'=>'https://goss.veer.com/creative/vcg/veer/800water/veer-312814457.jpg',
        'sort_num'=>rand(1,10),
        'on_sale'=>true
    ];
});
