<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {
    return [
        'name' => md5(uniqid(microtime(true),true)),
        'sort_num' => rand(1,100),
        'on_sale'=> true

    ];
});
