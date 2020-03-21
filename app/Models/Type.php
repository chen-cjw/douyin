<?php

namespace App\Models;

class Type extends Model
{
    protected $fillable = [
        'name_zh', 'name_en', 'image_url', 'sort_num', 'on_sale'
    ];
}
