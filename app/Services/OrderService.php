<?php

namespace App\Services;

use App\Models\CouponCode;
use App\Exceptions\CouponCodeUnavailableException;
use App\Models\Product;
use App\User;
use App\Models\UserAddress;
use App\Models\Order;
use App\Jobs\CloseOrder;
use Carbon\Carbon;
use Dingo\Api\Exception\ResourceException;

class OrderService
{
    public function index()
    {
        return auth('api')->user()->cartItems()->paginate();
    }

    public function store($user, $orderRequest, $coupon=null)
    {
        // 如果传入了优惠券，则先检查是否可用
        if ($coupon) {
            // 但此时我们还没有计算出订单总金额，因此先不校验
            $coupon->checkAvailable();
        }

        $order = \DB::transaction(function () use ($user, $orderRequest, $coupon) {
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

            if ($coupon) {
                // 总金额已经计算出来了，检查是否符合优惠券规则
                $coupon->checkAvailable($totalAmount);
                // 把订单金额修改为优惠后的金额
                $totalAmount = $coupon->getAdjustedPrice($totalAmount);
                // 将订单与优惠券关联
                $order->couponCode()->associate($coupon);
                // 增加优惠券的用量，需判断返回值
                if ($coupon->changeUsed() <= 0) {
                    throw new CouponCodeUnavailableException('该优惠券已被兑完');
                }
            }

            // 更新订单总金额
            $order->update(['total_amount' => $totalAmount]);
            // 将下单的商品从购物车中移除
            $productIds = collect($items)->pluck('product_id');
            $user->cartItems()->whereIn('product_id', $productIds)->delete();
            return $order;
        });
        dispatch(new CloseOrder($order, config('app.order_ttl')));
        return $order;

    }
}