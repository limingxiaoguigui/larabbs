<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        //所有用户Id数组
        $user_ids = User::all()->pluck('id')->toArray();
        //所有话题数组
        $topic_ids = Topic::all()->pluck('id')->toArray();
        //获取Faker实例
        $faker = app(Faker\Generator::class);

        $replies = factory(Reply::class)
            ->times(1000)
            ->make()
            ->each(function ($reply, $index) use ($user_ids, $topic_ids, $faker) {
                //从用户Id数组中随机去除一个值
                $reply->user_id = $faker->randomElement($user_ids);
                //从话题中随机取一个出来
                $reply->topic_id = $faker->randomElement($topic_ids);
            });
        //将数据集合转化数组并插入数据库
        Reply::insert($replies->toArray());
    }
}