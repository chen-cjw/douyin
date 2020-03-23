<?php
namespace App\Transformers;
use App\Models\Banner;
use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {
        return [
            'id' => $product->id,
            'title' => $product->title,
            'description' => $product->description,
            'image_url' => $product->image_url,
            'commission_rate' => $product->commission_rate,
            'commission' => $product->commission,
            'discounted_price' => $product->discounted_price,
            'price' => $product->price,
            'favourable_price' => $product->favourable_price,
            'vermicelli_consumption' => $product->vermicelli_consumption,
            'sample_quantity' => $product->sample_quantity,
            'support_dou' => $product->support_dou,
            'support_directional' => $product->support_directional,
            'copy_link' => $product->copy_link,
            'activity_countdown' => $product->activity_countdown,
            'sort_num'=>$product->sort_num,
            'on_sale'=>$product->on_sale,
            'type_id'=>$product->type_id,
            'category_id'=>$product->category_id,
            'created_at' => $product->created_at->toDateTimeString(),
            'updated_at' => $product->updated_at->toDateTimeString(),
        ];
    }
}