<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Transformers\CategoryTransformer;

class CategoriesController extends Controller
{
    //分类列表
    public function index()
    {
        return $this->response->collection(Category::all(), new CategoryTransformer());
    }
}