<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {
    $strs="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
    $name=substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),10);
    return [
        'name' => $faker->unique()->name,
        'sort_num' => rand(1,100),
        'on_sale'=> true

    ];
});
