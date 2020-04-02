<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Type::class, function (Faker $faker) {
    $strs="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
    $name=substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),10);
    return [
        'name_zh'=>$faker->unique()->username,
        'name_en'=> 'name_en',
        'image_url'=>'https://goss.veer.com/creative/vcg/veer/800water/veer-317591227.jpg',
        'sort_num'=>rand(1,100),
        'on_sale'=>true

    ];
});
