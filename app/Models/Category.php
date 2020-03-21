<?php

namespace App\Models;


class Category extends Model
{
    protected $fillable = [
        'name', 'sort_num', 'on_sale'
    ];

}
