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
        auth('api')->user()->addresses()->create($this->parameter($request));
        return $this->response->created();
    }

    public function update($user_address, UserAddressRequest $request)
    {
        auth()->user()->addresses()->where('id',$user_address)->update($this->parameter($request));
        return $this->response->created();
    }

    protected function parameter($request)
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
        return $data;
    }

    public function destroy($id)
    {
        auth()->user()->addresses()->where('id',$id)->delete();
        return $this->response->noContent();
    }
    
    // 每次使用默认的地址
    public function setDefault($id)
    {
        auth()->user()->addresses()->where('id',$id)->update([
            'last_used_at'=>date('Y-m-d H:i:s'),
        ]);

        return $this->response->created();
    }
}
