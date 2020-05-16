<?php

namespace App\Http\Requests;

use App\Models\Product;

class AddCartRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => [
                'required',
                function ($attribute, $value, $fail) {

                    if (!$product = Product::find($value)) {
                        return $fail('该商品不存在');
                    }
                    if (!$product->on_sale) {
                        return $fail('该商品未上架');
                    }
                    // sample_quantity
                    if ($product->sample_quantity === 0) {
                        return $fail('样品已售完');
                    }
                    if ($this->input('sample_quantity') > 0 && $product->sample_quantity < $this->input('sample_quantity')) {
                        return $fail('该商品库存不足');
                    }
                },
            ],
            'sample_quantity' => ['required', 'integer', 'min:1'],
        ];
    }
    public function attributes()
    {
        return [
            'sample_quantity' => '样品数量'
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => '请选择商品'
        ];
    }
}
