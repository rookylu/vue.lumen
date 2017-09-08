<?php

use App\Models\ManorOwner;
use Illuminate\Database\Seeder;

class ManorOwnersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 填充好多用户
        factory(ManorOwner::class, 50)->create();
    }
}
