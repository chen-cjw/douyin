<?php

namespace App\Models;

use Dingo\Api\Exception\InternalHttpException;

class Product extends Model
{
    protected $fillable = [
        'title','description','image_url', 'commission_rate','commission', 'discounted_price', 'price', 'favourable_price', 'vermicelli_consumption',
        'sample_quantity','support_dou','support_directional','copy_link','activity_countdown','type_id','category_id', 'sort_num', 'on_sale'
    ];
    public function decreaseStock($sampleQuantity)
    {
        if ($sampleQuantity < 0) {
            throw new InternalHttpException('减库存不可小于0');
        }

        return $this->where('id', $this->id)->where('sample_quantity', '>=', $sampleQuantity)->decrement('sample_quantity', $sampleQuantity);
    }

    public function addStock($sampleQuantity)
    {
        if ($sampleQuantity < 0) {
            throw new InternalHttpException('加库存不可小于0');
        }
        $this->increment('sample_quantity', $sampleQuantity);
    }
}
