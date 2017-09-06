<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateManorOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manor_owners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('real_name', 50)->index()->comment('名字');
            $table->string('cellphone', 32)->default('')->comment('手机号');
            $table->enum('gender', ['male', 'female', 'unkown'])->default('unkown')->comment('性别');
            $table->string('company_name', 64)->default('')->comment('公司名称');
            $table->string('desc', 255)->default('')->comment('描述');
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
        Schema::dropIfExists('manor_owners');
    }
}
