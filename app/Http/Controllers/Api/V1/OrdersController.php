<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserAddress;
use App\Transformers\OrderTransformer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\Translation\Exception\InvalidResourceException;

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
                    throw new InvalidResourceException('该商品库存不足');
//                    throw new InvalidRequestException('该商品库存不足');
                }
            }
            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车中移除
            $productIds = collect($items)->pluck('product_id');
            $user->cartItems()->whereIn('product_id', $productIds)->delete();
//            $this->dispatch(new CloseOrder($order, config('app.order_ttl')));

            return $this->response->item($order,new OrderTransformer());
        });
//        return $order;
//        $this->dispatch(new CloseOrder($order, config('app.order_ttl')));
        return $this->response->item($order,new OrderTransformer());
    }
}
