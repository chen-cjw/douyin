<?php
namespace App\Transformers;
use App\Models\Banner;
use App\Models\OrderItem;
use League\Fractal\TransformerAbstract;

class OrderItemTransformer extends TransformerAbstract
{

    public function transform(OrderItem $orderItem)
    {
        return [
            'id' => $orderItem->id,
            'sample_quantity' => $orderItem->sample_quantity,
            'price' => $orderItem->price,
            'rating' => $orderItem->rating,
            'review' => $orderItem->review, // 用户评价
            'reviewed_at' => $orderItem->reviewed_at,// 评价时间
            'created_at' => $orderItem->created_at->toDateTimeString(),
            'updated_at' => $orderItem->updated_at->toDateTimeString(),
        ];
    }
}