<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Banner;
use App\Transformers\BannerTransformer;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    public function index(Banner $banner)
    {
        $banners = $banner->orderSort()->get();
        return $this->response->collection($banners,new BannerTransformer());
    }
}
