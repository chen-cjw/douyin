<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Product $product,$category_id,$type_id,Request $request)
    {
        if($category_id) {
            $product = $product->where('category_id',$category_id);
        }
        if($type_id) {
            $product = $product->where('type_id',$type_id);
        }

        // 搜索商品名
        if($title = $request->title) {
            $product = $product->where('title','like','%'.$title.'%');
        }

        // 佣金排序
        if($commission = $request->commission) {
            $product = $product->orderBy('commission', $commission);
        }

        // 样品数量
        if($sampleQuantity = $request->sample_quantity) {
            $product = $product->orderBy('sample_quantity', $sampleQuantity);
        }

        // 时间搜索
        if($startTime = $request->start_time && $endTime = $request->end_time) {
            $product = $product->whereBetween('created_at', [$startTime, $endTime]);
        }

        // 折后价排序(价格)
        if($discountedPrice = $request->discounted_price) {
            $product = $product->orderBy('discounted_price', $discountedPrice);
        }

        $products = $product->orderBy('sort_num','desc')->where('on_sale',true)->paginate();
        return $this->response->paginator($products, new ProductTransformer());
    }

    public function show(Product $product, $id)
    {
       $product = $product->orderSort()->findOrFail($id);
       return $this->response->item($product, new ProductTransformer());
    }
}
