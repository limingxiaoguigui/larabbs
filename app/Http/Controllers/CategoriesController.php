<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\Category;
use App\Models\User;
use App\Models\Link;

class CategoriesController extends Controller
{
    //分类话题列表
    public function show(Category $category, Request $request, Topic  $topic, User $user, Link $link)
    {
        //读取分类Id的相关话题每页20条
        $topics = $topic
            ->withOrder($request->order)
            ->where('category_id', $category->id)
            ->paginate(20);
        //活跃用户列表
        $active_users = $user->getActiveUsers();
        //资源链接
        $links = $link->getAllCached();
        //分配到页面
        return view('topics.index', compact('topics', 'category', 'active_users', 'links'));
    }
}