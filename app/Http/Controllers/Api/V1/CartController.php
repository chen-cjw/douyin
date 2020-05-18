<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Transformers\CartItemTransformer;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth('api')->user()->cartItems()->paginate();
        return $this->response->paginator($cartItems,new CartItemTransformer());
    }
    
    public function store(AddCartRequest $request)
    {
        $user   = auth('api')->user();
        $productId  = $request->input('product_id');
        $sampleQuantity = $request->input('sample_quantity');

        // 从数据库中查询该商品是否已经在购物车中
        if ($cart = $user->cartItems()->where('product_id', $productId)->first()) {
            // 如果存在则直接叠加商品数量
            $cart->update([
                'sample_quantity' => $cart->sample_quantity + $sampleQuantity,
            ]);
        } else {
            // 否则创建一个新的购物车记录
            $cart = new CartItem(['sample_quantity' => $sampleQuantity]);
            $cart->user()->associate($user);
            $cart->product()->associate($productId);
            $cart->save();
        }
        return $this->response->created();
    }

    // 删除购物车商品
    public function remove($productIds)
    {
        // 可以传单个 ID，也可以传 ID 数组
        if (!is_array($productIds)) {
            $productIds = explode(',',$productIds);
        }
        auth('api')->user()->cartItems()->whereIn('product_id', $productIds)->delete();
        return $this->response->noContent();
    }
}
