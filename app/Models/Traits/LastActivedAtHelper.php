<?php
namespace App\Models\Traits;

use  Redis;
use Carbon\Carbon;

/**
 * 用户的随后活跃时间
 */
trait LastActivedAtHelper
{
    //缓存相关
    protected  $hash_prefix  = "larabbs_last_actived_at_";
    protected  $field_prefix = "user_";

    //缓存用户活跃时间到Redis
    public  function  recordLastActivedAt()
    {

        //Redis 哈希表的命名，如larabbs_last_actived_at_2017-10-21
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());
        //字段名称  如user_1
        $field = $this->getHashField();
        //当前时间  如  2017-10-21 08:35:36
        $now = Carbon::now()->toDateTimeString();
        //数据写入redis字段存在会被更新
        Redis::hSet($hash, $field, $now);
    }

    //将Redis数据同步导数据库
    public function  syncUserActivedAt()
    {

        //redis哈希表的命名 larabbs_last_actived_at_2017-10-11
        $hash = $this->getHashFromDateString(Carbon::yesterday()->toDateString());

        //congredis获取所有的数据
        $dates = Redis::hGetAll($hash);

        //遍历并同步到数据库中
        foreach ($dates as $user_id => $actived_at) {
            # code...
            //将user_1转化为1
            $user_id = str_replace($this->field_prefix, '', $user_id);
            //只有当前用户存在时才更新到数据库
            if ($user = $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }
        //以数据库为中心的存储，同步就可以删除
        Redis::del($hash);
    }

    //获取最后活跃时间
    public  function getLastActivedAtAttribute($value)
    {

        //Redis哈希表的命名如 larabbs_last_actived_at_2017-10-21
        $hash =  $this->getHashFromDateString(Carbon::now()->toDateString());
        //字段名称，user_1;
        $field = $this->getHashField();
        //三元运算符,优选选择redis中的 数据，否则用数据库中的数据
        $datetime = Redis::hGet($hash, $field) ?: $value;
        //如果存在返回时间Carbon的实体
        if ($datetime) {
            return  new  Carbon($datetime);
        } else {
            // 否则使用用户的注册时间
            return $this->created_at;
        }
    }
    //得到hash表
    public  function  getHashFromDateString($date)
    {
        //redis哈希表的命名如：larabbs_last_actived_at_2017-10-11

        return  $this->hash_prefix . $date;
    }
    //得到hash表字段
    public  function  getHashField()
    {

        //字段名称
        return   $this->field_prefix . $this->id;
    }
}