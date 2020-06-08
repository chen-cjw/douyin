<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\AuthRequest;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    // 一个是登陆
    public function store(AuthRequest $request)
    {
        $user = User::find(1);
        $token = Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token)->setStatusCode(201);

        $code = $request->code;
        // 小程序
        try {
            $app = app('wechat.mini_program');
            $sessionUser = $app->auth->session($code);
            $openid = $sessionUser['openid'];
            $user = User::where('openid', $openid)->first();

            if (!$user) {
                $user = User::create([
                    'openid' => $openid,
                ]);
            }
            $token = Auth::guard('api')->fromUser($user);
        } catch (\Exception $e) {
            // UnauthorizedHttpException
            throw new UnauthorizedHttpException('','授权失败,请重新授权');
        }
        return $this->respondWithToken($token)->setStatusCode(201);

    }

    // 个人中心
    public function meShow()
    {
        return $this->response->item(auth('api')->user(),new UserTransformer());
    }
    public function destroy()
    {
        Auth::guard('api')->logout();
        return $this->response->noContent();
    }
    protected function respondWithToken($token)
    {

        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 120
        ]);
    }
}
