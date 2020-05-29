<?php
namespace App\Transformers;
use App\Models\CouponCode;
use League\Fractal\TransformerAbstract;

class CouponCodesTransformer extends TransformerAbstract
{

    public function transform(CouponCode $couponCode)
    {
        return [
            'id' => $couponCode->id,
            'name' => $couponCode->name,
            'code' => $couponCode->code,
            'type' => $couponCode->type,
            'value' => $couponCode->value,
            'total' => $couponCode->total,
            'used' => $couponCode->used,
            'min_amount' => $couponCode->min_amount,
            'not_before' => $couponCode->not_before,
            'not_after' => $couponCode->not_after,
            'enabled' => $couponCode->enabled,
            'created_at' => $couponCode->created_at->toDateTimeString(),
            'updated_at' => $couponCode->updated_at->toDateTimeString(),
        ];
    }
}