<?php
namespace  App\Models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Cache;
use DB;

/**
 * 活跃用户
 */
trait ActiveUserHelper
{
    //用于存放临时的用户数据
    protected  $users = [];
    //配置信息
    protected  $topic_weight = 4;  //话题权重
    protected  $reply_weight = 1; //回复权重
    protected  $pass_days = 7;  //多少天内发表过内容
    protected  $user_number = 6; //取出多少个用户
    //缓存配置相关
    protected  $cache_key = "larabbs_active_users";
    protected $cache_expire_in_minutes = 65;

    //得到活跃用户
    public function  getActiveUsers()
    {
        //尝试从缓存中取出cache_key对应的数据。如果取得到，便直接返回数据源
        //否则运行匿名函数中的代码取出活跃用户，返回时同时做缓存
        return  Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function () {

            return  $this->calculateActiveUsers();
        });
    }
    //计算并缓存活跃用户的数据
    protected  function  calculateAndCacheActiveUsers()
    {
        //获取活跃用户列表
        $active_users = $this->calculateActiveUsers();
        //缓存活跃用户
        $this->cacheActiveUsers($active_users);
    }
    //获取活跃用户
    private  function  calculateActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();
        //数组按照得分排序
        $users = array_sort($this->users, function ($user) {
            return  $user['score'];
        });
        //我们需要的是倒序，高分靠前，第二参数为保持数组的key不变

        $users = array_reverse($users, true);
        //只获取我们想要都额位置
        $users = array_slice($users, 0, $this->user_number, true);
        //新建一个空集合
        $active_users = collect();
        //新建一个空集合
        foreach ($users as $user_id => $user) {
            //找寻下是否可以找到的客户
            $user = $this->find($user_id);
            //如果是数据库里有该用户
            if ($user) {
                //将此用户实体放入集合的末尾
                $active_users->push($user);
            }
            # code...
        }

        //返回数据
        return  $active_users;
    }

    //计算话题分数
    private  function calculateTopicScore()
    {
        //从话题数据表里取出限定时间内，放表过话题的用户
        //并且同时取出此用户此段时间内发表话题的数量
        $topic_users = Topic::query()->select(DB::raw('user_id,count(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();
        //根据话题数量计算得分
        foreach ($topic_users as  $value) {

            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;
        }
    }
    //计算回复分数
    private  function  calculateReplyScore()
    {
        //从回复数据表里取出限定时间内的有发表过回复的用户
        //并且同时取出用户此段时间内发布回复的数量
        $reply_users = Reply::query()
            ->select(DB::raw('count(*) as  reply_count,user_id'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();
        //根据回复数量的到得分
        foreach ($reply_users as $value) {
            $reply_score = $value->reply_count * $this->reply_weight;
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $reply_score;
            } else {
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }
    //缓存数据
    private   function cacheActiveUsers($active_users)
    {
        //将数据存入缓存
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_minutes);
    }
}