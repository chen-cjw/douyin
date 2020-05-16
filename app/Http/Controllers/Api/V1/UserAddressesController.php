<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use App\Transformers\UserAddressTransformer;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    public function index()
    {
        $userAddress = auth('api')->user()->addresses()->orderBy('last_used_at','desc')->paginate();
        return $this->response->paginator($userAddress,new UserAddressTransformer());
    }

    public function store(UserAddressRequest $request)
    {
        $data = $request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]);
        $data['last_used_at'] = date('Y-m-d H:i:s');
        auth('api')->user()->addresses()->create($data);
        return $this->response->created();
    }

    public function update($user_address, UserAddressRequest $request)
    {
        auth()->user()->addresses()->where('id',$user_address)->update($request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]));
        return $this->response->created();
    }

    public function destroy($id)
    {
        auth()->user()->addresses()->where('id',$id)->delete();
        return $this->response->noContent();
    }
}
