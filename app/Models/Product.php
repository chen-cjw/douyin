<?php

namespace App\Models;

class Product extends Model
{
    protected $fillable = [
        'title','description','image_url', 'commission_rate','commission', 'discounted_price', 'price', 'favourable_price', 'vermicelli_consumption',
        'sample_quantity','support_dou','support_directional','copy_link','activity_countdown','type_id','category_id', 'sort_num', 'on_sale'
    ];

}
