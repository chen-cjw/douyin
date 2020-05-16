<?php
namespace App\Transformers;
use App\Models\CartItem;
use League\Fractal\TransformerAbstract;

class CartItemTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['product'];

    public function transform(CartItem $item)
    {
        return [
            'id' => $item->id,
            'sample_quantity' => $item->sample_quantity,
            'product_id' => $item->product_id,
        ];
    }

    public function includeProduct(CartItem $item)
    {
        return $this->item($item->product,new ProductTransformer());

    }
}