<?php
namespace App\Transformers;
use App\Models\OrderItem;
use League\Fractal\TransformerAbstract;

class OrderItemTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['product'];

    public function transform(OrderItem $orderItem)
    {
        return [
            'id' => $orderItem->id,
            'sample_quantity' => $orderItem->sample_quantity,
            'price' => $orderItem->price,
            'rating' => $orderItem->rating,
            'review' => $orderItem->review, // 用户评价
            'reviewed_at' => $orderItem->reviewed_at,// 评价时间
        ];
    }

    public function includeProduct(OrderItem $orderItem)
    {
        return $this->item($orderItem->product,new ProductTransformer());
    }

}