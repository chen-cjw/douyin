<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Model extends BaseModel
{
    public function orderSort()
    {
        return $this->orderBy('sort_num','desc')->where('on_sale',true);
    }
    public function getImageUrlAttribute()
    {
        // 如果 image 字段本身就已经是完整的 url 就直接返回
        if (Str::startsWith($this->attributes['image_url'], ['http://', 'https://'])) {
            return $this->attributes['image_url'];
        }
        return Storage::disk('admin')->url($this->attributes['image_url']);
    }
}
