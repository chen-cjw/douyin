<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Product::class, function (Faker $faker) {
    $strs="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
    $name=substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),10);
    return [
        'title'=>$name,
        'image_url'=>'https://goss.veer.com/creative/vcg/veer/800water/veer-153383746.jpg',
        'description'=>$faker->text(200),
        'commission_rate'=>rand(1,10),
        'discounted_price'=>rand(10,100),
        'price'=>rand(10,100),
        'favourable_price'=>rand(10,100),
        'vermicelli_consumption'=>rand(1000,100000),
        'sample_quantity'=>rand(1,100),
        'support_dou'=>1,
        'support_directional'=>1,
        'copy_link'=>$faker->url,
        'activity_countdown'=>$faker->date('Y-m-d'),
        'on_sale'=>true,
        'type_id'=>rand(1,5),
        'category_id'=>rand(1,5),
    ];
});
