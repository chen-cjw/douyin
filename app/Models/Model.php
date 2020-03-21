<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public function orderSort()
    {
        return $this->orderBy('sort_num','desc')->where('on_sale',true);
    }
}
