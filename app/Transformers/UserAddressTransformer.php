<?php
namespace App\Transformers;
use App\Models\Banner;
use App\Models\UserAddress;
use League\Fractal\TransformerAbstract;

class UserAddressTransformer extends TransformerAbstract
{

    public function transform(UserAddress $address)
    {
        return [
            'id' => $address->id,
            'province' => $address->province,
            'city' => $address->city,
            'district' => $address->district,
            'address' => $address->address,
            'zip' => $address->zip,
            'contact_name' => $address->contact_name,
            'contact_phone' => $address->contact_phone,
            'last_used_at' => $address->last_used_at,
            'full_address' => $address->full_address,
            'default' => $address->default,
            'created_at' => $address->created_at->toDateTimeString(),
            'updated_at' => $address->updated_at->toDateTimeString(),
        ];
    }
}