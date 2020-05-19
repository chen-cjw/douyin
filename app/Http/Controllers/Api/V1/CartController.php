<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use App\Transformers\CartItemTransformer;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return $this->response->paginator($this->cartService->index(),new CartItemTransformer());
    }
    
    public function store(AddCartRequest $request)
    {
        $productId  = $request->input('product_id');
        $sampleQuantity = $request->input('sample_quantity');
        $this->cartService->add($productId,$sampleQuantity);
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
