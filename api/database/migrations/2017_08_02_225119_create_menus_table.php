<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icon', 100)->nullable()->comment('菜单图标');
            $table->string('name')->nullable()->comment('菜单名');
            $table->string('route', 255)->nullable()->comment('链接');
			$table->integer('bpid')->unsigned()->default(1)->comment('父菜ID');
			$table->integer('mpid')->signed()->default(-1)->comment('决定是否显示在菜单中');
			$table->integer('sort_order')->unsigned()->default(0)->comment('排序');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menus');
    }
}
