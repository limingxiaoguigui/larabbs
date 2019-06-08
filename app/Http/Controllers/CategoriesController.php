<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;

class CategoriesController extends Controller
{
    //分类话题列表
    public function show(Category $category)
    {
        //读取分类Id的相关话题每页20条
        $topics = Topic::where('category_id', $category->id)->paginate(20);
        //分配到页面
        return view('topics.index', compact('topics', 'category'));
    }
}