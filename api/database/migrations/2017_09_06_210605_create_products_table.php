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
            $table->date('signed_at')->comment('简约时间');
            $table->date('payment_at')->comment('付款时间');
            $table->decimal('money', 10, 2)->default(0.00)->comment('付款金额');
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
