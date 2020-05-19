<?php

namespace App\Services;

use Auth;
use App\Models\CartItem;

class CartService
{
    public function index()
    {
        return auth('api')->user()->cartItems()->paginate();
    }

    public function add($productId,$sampleQuantity)
    {
        $user   = auth('api')->user();
        //$productId  = $request->input('product_id');
        //$sampleQuantity = $request->input('sample_quantity');

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
    }
}