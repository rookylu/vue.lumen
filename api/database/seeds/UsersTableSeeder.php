<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'real_name' => '义薄云天',
            'email' => 'qiaogqiang@163.com',
            'password' => app('hash')->make(123456),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        // 填充好多用户
        factory(User::class, 50)->create();
    }
}
