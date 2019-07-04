<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Transformers\NotificationTransformer;


class NotificationsController extends Controller
{
    //消息通知列表
    public  function index()
    {

        $notifications = $this->user->notifications()->paginate(20);


        return  $this->response->paginator($notifications, new NotificationTransformer());
    }
    //消息统计
    public  function stats()
    {
        return  $this->response->array(['unread_count' => $this->user()->notification_count]);
    }
    //标记消息为已读
    public  function  read()
    {
        $this->user()->markAsRead();

        return $this->response->noContent();
    }
}