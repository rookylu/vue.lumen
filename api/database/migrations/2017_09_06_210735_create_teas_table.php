<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->default(0)->comment('产品ID');
            $table->integer('year')->default(0)->comment('年份');
            $table->integer('period_index')->default(0)->comment('期次编号');
            $table->datetime('delivery_time')->comment('实际交付日期');
            $table->datetime('delivery_time_deadline')->comment('交付时间截止日期');
            $table->unsignedTinyInteger('is_delivered')->default(0)->comment('是否已交付');
            $table->string('remark', 255)->default('')->comment('交付备注, 例如: 交付数量、地址等备注信息');
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
        Schema::dropIfExists('teas');
    }
}
