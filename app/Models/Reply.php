<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    //回复和话题之间的关系
    public function topic()
    {

        return $this->belongsTo(Topic::class);
    }
    //回复和用户之间的关联关系
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}