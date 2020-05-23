<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // todo 2小时超时，支付就会有问题
    public function __construct()
    {
        $this->app = app('wechat.payment');

    }
    /**
     * 唤起支付操作，
     **/
    public function payByWechat(Order $order, Request $request) {
        // 校验权限
        $order = auth()->user()->orders()->where('id',$order)->firstOrFail();
        // 校验订单状态
        if ($order->paid_at || $order->closed) {
            throw new ResourceException('订单状态不正确');
        }

        $result = $this->app->order->unify([
            'body' => '支付 徐州鑫发商贸 的订单：'.$order->no,
            'out_trade_no' => $order->no,
            'total_fee' => $order->total_amount * 100,
            //'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
            'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid' => auth('api')->user()->openid,
            'trade_type' => 'JSAPI', // 请对应换成你的支付方式对应的值类型
        ]);
        return $result;
    }

    // 回调接口，微信的支付没有前端回调只有服务器端回调：
    public function wechatNotify()
    {
        $response = $this->app->handlePaidNotify(function($message, $fail){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = Order::where('no',$message['out_trade_no'])->first();//  $order =  ($message['out_trade_no']);

            // 订单不存在则告知微信支付
            if (!$order) {
                return 'fail';
            }
            // 订单已支付
            if ($order->paid_at) {
                // 告知微信支付此订单已处理
                return true;
                //return app('wechat_pay')->success();
            }

//            if (!$order || $order->paid_at) { // 如果订单不存在 或者 订单已经支付过了
//                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
//            }

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功 // 将订单标记为已支付
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $order->paid_at = Carbon::now(); // 更新支付时间为当前时间
                    $order->payment_method = 'wechat'; // 支付方式
                    $order->payment_no = $message['transaction_id']; // 支付平台订单号
                    //$order->status = 'paid';

                    // 用户支付失败
                } elseif (array_get($message, 'result_code') === 'FAIL') {
                    $order->status = 'paid_fail';
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        return $response;//  $response->send();

    }
    // 退款
    public function wechatRefundNotify(Request $request)
    {
        // 给微信的失败响应
        $failXml = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';
        $data = app('wechat_pay')->verify(null, true);

        // 没有找到对应的订单，原则上不可能发生，保证代码健壮性
        if(!$order = Order::where('no', $data['out_trade_no'])->first()) {
            return $failXml;
        }

        if ($data['refund_status'] === 'SUCCESS') {
            // 退款成功，将订单退款状态改成退款成功
            $order->update([
                'refund_status' => Order::REFUND_STATUS_SUCCESS,
            ]);
        } else {
            // 退款失败，将具体状态存入 extra 字段，并表退款状态改成失败
            $extra = $order->extra;
            $extra['refund_failed_code'] = $data['refund_status'];
            $order->update([
                'refund_status' => Order::REFUND_STATUS_FAILED,
                'extra' => $extra
            ]);
        }

        return app('wechat_pay')->success();
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
