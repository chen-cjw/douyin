<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Category;
use App\Transformers\CategoryTransformer;

class CategoriesController extends Controller
{
    public function index(Category $category)
    {
        $categories = $category->orderSort()->get();
        return $this->response->collection($categories,new CategoryTransformer());
    }
}
