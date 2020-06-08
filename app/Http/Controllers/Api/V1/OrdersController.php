<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\ApplyRefundRequest;
use App\Http\Requests\OrderRequest;
use App\Jobs\CloseOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserAddress;
use App\Services\OrderService;
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
    
    public function store(OrderRequest $orderRequest,OrderService $orderService)
    {
        $user  = auth('api')->user();
        // 开启一个数据库事务
        $order = $orderService->store($user, $orderRequest,$orderRequest->input('coupon_code')?:null);
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
