<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sys_no', 32)->unique()->default('')->comment('系统编号');
            $table->string('product_name', 32)->default('')->comment('产品标题');
            $table->integer('customer_id')->default(0)->comment('客户ID');
            $table->date('signed_at')->comment('简约时间');
            $table->date('payment_at')->comment('付款时间');
            $table->date('first_at')->comment('首期交付时间');
            $table->unsignedTinyInteger('first_phase')->default(0)->comment('首期交付');
            $table->decimal('unit_price', 10, 2)->default(9800.00)->comment('单价金额');
            $table->integer('num')->default(1)->comment('亩数');
            $table->decimal('total_money', 10, 2)->default(0.00)->comment('总金额');
            $table->unsignedTinyInteger('is_disabled')->default(0)->comment('是否禁用');
            $table->integer('creator_id')->default(0)->comment('创建人ID');
            $table->integer('creator_name')->default(0)->comment('创建人名称');
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
        Schema::dropIfExists('products');
    }
}
