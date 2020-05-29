<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\CouponCode;
use App\Transformers\CouponCodesTransformer;
use Carbon\Carbon;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CouponCodesController extends Controller
{
    // 这里的参数是优惠券code
    public function show($code)
    {
        // 判断优惠券是否存在
        if (!$record = CouponCode::where('code', $code)->first()) {
            throw new NotFoundHttpException('判断优惠券是不存在');
        }
        // 优惠券是否满足使用条件
        $record->checkAvailable();
        return $this->response->item($record,new CouponCodesTransformer());
    }
}
