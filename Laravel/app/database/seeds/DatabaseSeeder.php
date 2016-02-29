<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('SentrySeeder');
	}

}


class SentrySeeder extends Seeder {

    public function run()
    {
        // 清空数据
        DB::table('users')->delete();
        DB::table('groups')->delete();
        DB::table('users_groups')->delete();

        // 创建用户
        Sentry::getUserProvider()->create(array(
            'email'      => '101010@qq.com',
            'password'   => "101010",
            'first_name' => '超',
            'last_name'  => '袁',
            'activated'  => 1,
        ));

        // 创建用户组
        Sentry::getGroupProvider()->create(array(
            'name'        => 'Admin',
            'permissions' => ['admin' => 1],
        ));

        // 将用户加入用户组
        $adminUser  = Sentry::getUserProvider()->findByLogin('101010@qq.com');
        $adminGroup = Sentry::getGroupProvider()->findByName('Admin');
        $adminUser->addGroup($adminGroup);
    }
}


class UserTableSeeder extends Seeder {
    public function run()
    {
		$hashed = Hash::make('secret');
        User::create(
			array('username'=>'James','email' => 'james@gmail.com','password'=>$hashed)
		);
    }
}
