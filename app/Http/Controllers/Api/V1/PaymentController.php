<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\Translation\Exception\InvalidResourceException;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->app = app('wechat.payment');

    }
    /**
     * 支付
    **/
    public function payByWechat(Order $order, Request $request) {
        // 校验权限
        $order = auth()->user()->orders()->where('id',$order)->firstOrFail();
        // 校验订单状态
        if ($order->paid_at || $order->closed) {
            throw new InvalidResourceException('订单状态不正确');
            //throw new InvalidRequestException('订单状态不正确');
        }

        $result = $this->app->order->unify([
            'body' => '腾讯充值中心-QQ会员充值',
            'out_trade_no' => '20150806125346',
            'total_fee' => 88,
            'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
            'openid' => 'oUpF8uMuAJO_M2pxb1Q9zNjWeS6o',
        ]);


        // scan 方法为拉起微信扫码支付
        return app('wechat_pay')->scan([
            'out_trade_no' => $order->no,  // 商户订单流水号，与支付宝 out_trade_no 一样
            'total_fee' => $order->total_amount * 100, // 与支付宝不同，微信支付的金额单位是分。
            'body'      => '支付 Laravel Shop 的订单：'.$order->no, // 订单描述
        ]);
    }


    // 微信支付订单的查询
    public function queryByOutTradeNumber()
    {
        $this->app->order->queryByOutTradeNumber("商户系统内部的订单号（out_trade_no）");// 商户系统内部的订单号（out_trade_no）
    }

    public function queryByTransactionId()
    {
        $this->app->order->queryByTransactionId(""); //微信订单号（transaction_id）
    }
}
