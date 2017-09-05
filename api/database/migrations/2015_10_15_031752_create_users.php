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
            $table->string('name', 50)->index()->comment('名字');
            $table->string('password')->nullable()->comment('密码');
            $table->string('avatar', 255)->nullable()->comment('头像');
			$table->integer('role')->unsigned()->default(1)->comment('角色: 1管理员 2一般用户');
			$table->string('permissions', 255)->nullable()->comment('权限');
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
