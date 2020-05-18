<?php
namespace App\Transformers;
use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['items'];

    public function transform(Order $order)
    {
        return [
            'id' => $order->id,
            'no' => $order->no,
            'address' => $order->address,
            'total_amount' => $order->total_amount,
            'remark' => $order->remark,
            'paid_at' => $order->paid_at,
            'payment_method' => $order->payment_method,
            'payment_no' => $order->payment_no,
            'refund_status' => $order->refund_status,
            'refund_no' => $order->refund_no,
            'closed' => $order->closed,
            'reviewed' => $order->reviewed,
            'ship_status' => $order->ship_status,
            'ship_data' => $order->ship_data,
            'extra' => $order->extra,
            'created_at' => $order->created_at->toDateTimeString(),
            'updated_at' => $order->updated_at->toDateTimeString(),
        ];
    }

    public function includeItems(Order $order)
    {
        return $this->collection($order->items,new OrderItemTransformer());
    }

}