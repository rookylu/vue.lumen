<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->default(0)->comment('产品ID');
            $table->integer('year')->default(0)->comment('年份');
            $table->decimal('stayed_time', 2, 1)->default(0.0)->comment('住宿时长, 单位: 天');
            $table->decimal('total_time', 2, 1)->default(2.5)->comment('总时间, 单位: 天, 默认2天半，即3天2晚');
            $table->unsignedTinyInteger('is_disabled')->default(0)->comment('是否禁用');
            $table->integer('updator_id')->default(0)->comment('修改人ID');
            $table->integer('updator_name')->default(0)->comment('修改人名称');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacations');
    }
}
