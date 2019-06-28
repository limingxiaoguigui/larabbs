<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use Notifiable {
    notify as  protected laravelNotify;
    }
    use MustVerifyEmailTrait;
    use HasRoles;
    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;

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
        'name', 'phone', 'email', 'password', 'introduction', 'avatar', 'weixin_openid', 'weixin_unionid'
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
    //密码修改器
    public function setPasswordAttribute($value)
    {
        //如果值的长度等于60，即认为是已经加密的情况
        if (strlen($value) != 60) {
            //
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }
    //头像修改器
    public function  setAvatarAttribute($path)
    {
        //如果不是`http开头的`，那就是从后台上传的需要补全url
        if (!starts_with($path, 'http')) {
            //拼凑完整的Url
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }
        $this->attributes['avatar'] = $path;
    }
}