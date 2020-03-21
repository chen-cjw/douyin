<?php
namespace App\Transformers;
use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    public function transform(Category $product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'sort_num' => $product->sort_num,
            'on_sale' => $product->on_sale,
            'created_at' => $product->created_at->toDateTimeString(),
            'updated_at' => $product->updated_at->toDateTimeString(),
        ];
    }
}