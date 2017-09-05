<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique()->index()->comment('邮箱');
            $table->string('real_name', 50)->index()->comment('名字');
            $table->string('nick_name', 50)->default('')->comment('昵称');
            $table->string('cellphone', 32)->default('')->comment('手机号');
            $table->string('wx_openid', 64)->default('')->comment('微信openid');
            $table->string('password')->nullable()->comment('密码');
            $table->string('avatar', 255)->nullable()->comment('头像');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('users');
    }
}
