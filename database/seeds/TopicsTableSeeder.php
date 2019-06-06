<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {

        //所有用户数组
        $user_ids = User::all()->pluck('id')->toArray();

        //所有分类
        $category_ids = Category::all()->pluck('id')->toArray();

        //获取$faker实例
        $faker = app(Faker\Generator::class);
        //话题数据
        $topics = factory(Topic::class)->times(100)
            ->make()
            ->each(function ($topic, $index) use ($faker, $user_ids, $category_ids) {

                //从用户数组中随机取出并赋值
                $topic->user_id = $faker->randomElement($user_ids);

                //随机一个分类
                $topic->category_id = $faker->randomElement($category_ids);
            });
        //将数据转化维数组并插入数据库
        Topic::insert($topics->toArray());
    }
}