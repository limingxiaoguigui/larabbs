<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];
    //分类和话题的一对一的关联模型
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //用户和话题的模型：一对一的关联模型
    public  function user()
    {
        return $this->belongsTo(User::class);
    }
    //排序逻辑
    public function scopeWithOrder($query, $order)
    {
        //不同的排序，使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();                # code...
                break;
        }
        //预加载防止N+1的问题
        return $query->with('user', 'category');
    }

    //按最近回复
    public  function scopeRecentReplied($query)
    {
        //当话题有新回复的时候，我们将编写逻辑来更新话题模型的reply_count属性
        //此时会自动触发框架对数据模型updated_at时间戳进行更新
        return $query->orderBy('updated_at', 'desc');
    }
    //按最近更新来排序
    public function scopeRecent($query)
    {
        //按创建时间排序
        return $query->orderBy('created_at', 'desc');
    }
    //路由处理
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }
}