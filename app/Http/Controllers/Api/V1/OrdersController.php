<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ApplyRefundRequest;
use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserAddress;
use App\Transformers\OrderTransformer;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = auth('api')->user()->orders()->orderBy('created_at','desc')->paginate();
        return $this->response->paginator($orders,new OrderTransformer());
    }
    public function show($id)
    {
        $order = auth('api')->user()->orders()->where('id',$id)->firstOrFail();
        return $this->response->item($order,new OrderTransformer());
    }
    
    public function store(OrderRequest $orderRequest)
    {
        $user  = auth('api')->user();
        // 开启一个数据库事务
        $order = \DB::transaction(function () use ($user, $orderRequest) {
            $address = $user->addresses()->where('id',$orderRequest->input('address_id'))->first();

            // 更新此地址的最后使用时间
            $address->update(['last_used_at' => Carbon::now()]);

            // 创建一个订单
            $order   = new Order([
                'address'      => [ // 将地址信息放入订单中
                    'address'       => $address->full_address,
                    'zip'           => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'       => $orderRequest->input('remark'),
                'total_amount' => 0,
            ]);
            // 订单关联到当前用户
            $order->user()->associate($user);
            // 写入数据库
            $order->save();

            $totalAmount = 0;
            $items       = $orderRequest->input('items');
            // 遍历用户提交的 SKU
            foreach ($items as $data) {
                $product  = Product::find($data['product_id']);
                // 创建一个 OrderItem 并直接与当前订单关联
                $item = $order->items()->make([
                    'sample_quantity' => $data['sample_quantity'],
                    'price'  => $product->discounted_price, // 折后价
                ]);
                $item->product()->associate($product);
                $item->save();
                $totalAmount += $product->discounted_price * $data['sample_quantity']; // 价格太多要注意
                // 减库存
                if ($product->decreaseStock($data['sample_quantity']) <= 0) {
                    throw new ResourceException('该商品库存不足');
                }
            }
            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);
            // 将下单的商品从购物车中移除
            $productIds = collect($items)->pluck('product_id');
            $user->cartItems()->whereIn('product_id', $productIds)->delete();
            $this->dispatch(new CloseOrder($order, config('app.order_ttl')));
            return $this->response->item($order,new OrderTransformer());
        });
        return $this->response->item($order,new OrderTransformer());
    }

    // 确认收货的接口
    public function received($id, Request $request)
    {
        $order = auth('api')->user()->orders()->where('id',$id)->first();
        // 判断订单的发货状态是否为已发货
        if ($order->ship_status !== Order::SHIP_STATUS_DELIVERED) {

            throw new ResourceException('发货状态不正确');
        }
        // 更新发货状态为已收到
        $order->update(['ship_status' => Order::SHIP_STATUS_RECEIVED]);

        return $this->response->created();
    }

    // 申请退款
    public function applyRefund($id, ApplyRefundRequest $request)
    {
        $order = auth()->user()->orders()->where('id',$id)->first();
        // 判断订单是否已付款
        if (!$order->paid_at) {
            throw new ResourceException('该订单未支付，不可退款');
        }
        // 判断订单退款状态是否正确
        if ($order->refund_status !== Order::REFUND_STATUS_PENDING) {
            throw new ResourceException('该订单已经申请过退款，请勿重复申请');
        }
        // 将用户输入的退款理由放到订单的 extra 字段中
        $extra                  = $order->extra ?: [];
        $extra['refund_reason'] = $request->input('reason');
        // 将订单退款状态改为已申请退款
        $order->update([
            'refund_status' => Order::REFUND_STATUS_APPLIED,
            'extra'         => $extra,
        ]);
        return $this->response->item($order,new OrderTransformer());
    }

}
