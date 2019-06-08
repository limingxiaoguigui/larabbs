<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'category_id', 'reply_count', 'view_count', 'last_reply_user_id', 'order', 'excerpt', 'slug'];
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
}