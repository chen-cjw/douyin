<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Type;
use App\Transformers\TypeTransformer;
use Illuminate\Http\Request;

class TypesController extends Controller
{
    public function index(Type $type)
    {
        $types = $type->orderSort()->get();
        return $this->response->collection($types,new TypeTransformer());
    }
}
