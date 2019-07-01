<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //可填充的数据
    protected  $fillable = ['type', 'path'];

    //一个用户有多张图片
    public  function  user()
    {

        return $this->belongsTo(User::class);
    }
}