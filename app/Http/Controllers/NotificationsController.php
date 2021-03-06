<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class NotificationsController extends Controller
{
    //初始化
    public function  __construct()
    {
        $this->middleware('auth');
    }

    //消息列表
    public function  index()
    {
        //获取登录用户的所有通知
        $notifications = Auth::user()->notifications()->paginate(20);
        //标记为已读，未读数清零
        Auth::user()->markAsRead();
        return  view('notifications.index', compact('notifications'));
    }
}