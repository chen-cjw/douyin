<?php
namespace App\Transformers;
use App\Models\Type;
use League\Fractal\TransformerAbstract;

class TypeTransformer extends TransformerAbstract
{

    public function transform(Type $type)
    {
        return [
            'id' => $type->id,
            'name_zh' => $type->name_zh,
            'name_en' => $type->name_en,
            'image_url' => $type->image_url,
            'sort_num' => $type->sort_num,
            'on_sale' => $type->on_sale,
            'created_at' => $type->created_at->toDateTimeString(),
            'updated_at' => $type->updated_at->toDateTimeString(),
        ];
    }
}