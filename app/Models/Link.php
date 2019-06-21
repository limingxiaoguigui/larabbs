<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Link extends Model
{
    protected  $fillable = ['title', 'link'];

    protected $cache_key = 'larabbs_links';
    protected $cache_expire_in_minutes = 1440;

    //获取所有缓存的资源链接
    public  function  getAllCached()
    {
        //尝试从缓存中取出cache_key对应的数据，如果能取到直接就返回数据。
        //否则运行匿名函数的代码取出links表的所有数据，返回同时并做缓存
        return  Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function () {
            return $this->all();
        });
    }
}