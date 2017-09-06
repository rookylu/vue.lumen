<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacation_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vid')->default(0)->comment('度假id');
            $table->decimal('stayed_time', 2, 1)->comment('度假时间段');
            $table->datetime('stayed_at')->comment('度假开始时间');
            $table->string('remark')->comment('度假备注: 同行人数、附加要求等等');
            $table->integer('creator_id')->default(0)->comment('创建人ID');
            $table->string('creator_name')->default('')->comment('创建人名称');
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
        Schema::dropIfExists('vacation_details');
    }
}
