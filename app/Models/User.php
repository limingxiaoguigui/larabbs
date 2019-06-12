<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Auth;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use Notifiable {
    notify as  protected laravelNotify;
    }
    use MustVerifyEmailTrait;

    public  function notify($instance)
    {
        //如果要通知的认识当前用户，就不必通知了
        if ($this->id == Auth::id()) {
            return;
        }
        //只有数据库类型通知才需要提醒
        if (method_exists($instance, 'toDatabase')) {
            $this->increment('notification_count');
        }
        $this->laravelNotify($instance);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //一个用户有多条评论
    public function replies()
    {

        return $this->hasMany(Reply::class);
    }

    //用户和话题的关系
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    //是不是当前用户
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }
    //已读消息通知
    public function  markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
}