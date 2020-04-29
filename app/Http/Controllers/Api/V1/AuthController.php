<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\AuthRequest;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 一个是登陆
    public function store(AuthRequest $request)
    {
        $code = $request->code;
        // 小程序
//        try {
            $app = app('wechat.mini_program');
            $sessionUser = $app->auth->session($code);
            return $sessionUser;
            $openid = $sessionUser['openid'];

            $user = User::where('openid', $openid)->first();
            if (!$user) {
                //         'openid','nickname','sex','language','city','province','country','avatar','unionid'
                $user = User::create([
                    'openid' => $openid,
                ]);
            }

//            dd($user);
//            $token = \Auth::guard('api')->fromUser($user);
//        } catch (\Exception $e) {
//            throw new \Exception('授权失败,请重新授权!');
//        }
//        return $this->respondWithToken($token,$openid)->setStatusCode(201);

    }

    // 个人中心
    public function meShow()
    {
        return $this->response->item($this->user(),new UserTransformer());
    }
    public function destroy()
    {
        Auth::guard('api')->logout();
        return $this->response->noContent();
    }
    protected function respondWithToken($token,$openid)
    {

        return $this->response->array([
            'access_token' => $token,
            'openid' => $openid,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 120
        ]);
    }
}
