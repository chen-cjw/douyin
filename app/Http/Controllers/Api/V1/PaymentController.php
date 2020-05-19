<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class PaymentController extends Controller
{
    /**
     * 支付
    **/
    public function payByWechat(Order $order, Request $request) {
        // 校验权限
        $this->authorize('own', $order);
        $order = auth()->user()->orders()->where('id',$order)->firstOrFail();
        // 校验订单状态
        if ($order->paid_at || $order->closed) {
            throw new InvalidResourceException('订单状态不正确');
            //throw new InvalidRequestException('订单状态不正确');
        }
        // scan 方法为拉起微信扫码支付
        return app('wechat_pay')->scan([
            'out_trade_no' => $order->no,  // 商户订单流水号，与支付宝 out_trade_no 一样
            'total_fee' => $order->total_amount * 100, // 与支付宝不同，微信支付的金额单位是分。
            'body'      => '支付 Laravel Shop 的订单：'.$order->no, // 订单描述
        ]);
    }
}
