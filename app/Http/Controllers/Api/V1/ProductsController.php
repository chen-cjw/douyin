<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Product $product,$category_id,$type_id)
    {
        if($category_id) {
            $product = $product->where('category_id',$category_id);
        }
        if($type_id) {
            $product = $product->where('type_id',$type_id);
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
