<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //获取$faker实例
        $faker = app(Faker\Generator::class);
        //头像假数据
        $avatars = [
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/s5ehp11z6s.png',
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/Lhd1SHqu86.png',
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/LOnMrqbHJn.png',
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/xAuDMxteQy.png',
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/ZqM7iaP4CR.png',
            'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/NDnzMutoxX.png',
        ];
        //生成数据集合
        $users = factory(User::class)->times(10)
            ->make()
            ->each(
                function ($user, $index) use ($faker, $avatars) {
                    //从头像数组中随机取出并赋值
                    $user->avatar = $faker->randomElement($avatars);
                }
            );
        //让隐藏字段可见，并将数据集合转换成数组
        $user_array = $users->makeVisible(['password', 'remember_token'])->toArray();
        //插入数据
        User::insert($user_array);
        //单独对第一个用户进行处理
        $user = User::find(1);
        $user->name = 'LMG';
        $user->email = 'htyzhliminggui@163.com';
        $user->avatar = 'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/ZqM7iaP4CR.png';
        $user->save();
        //初始化用户角色将一号用户指派为站长
        $user->assignRole('Founder');
        //将二号用户指派为管理员
        $user = User::find(2);
        $user->name = 'admin';
        $user->email = '971046892@qq.com';
        $user->save();
        $user->assignRole('Maintainer');
    }
}