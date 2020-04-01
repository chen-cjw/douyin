<?php
namespace App\Transformers;
use App\Models\Banner;
use League\Fractal\TransformerAbstract;

class BannerTransformer extends TransformerAbstract
{

    public function transform(Banner $banner)
    {
        return [
            'id' => $banner->id,
            'image_url' => $banner->image_url,
            'href_url' => $banner->href_url,
            'sort_num' => $banner->sort_num,
            'on_sale' => $banner->on_sale,
            'created_at' => $banner->created_at->toDateTimeString(),
            'updated_at' => $banner->updated_at->toDateTimeString(),
        ];
    }
}