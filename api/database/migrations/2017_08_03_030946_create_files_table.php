<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fid')->unsigned()->comment('文件夹id');
            $table->string('name', 255)->comment('文件名');
			$table->string('filename', 255)->comment('文件名称');
			$table->string('mime')->comment('文件名称');
            $table->string('desc')->nullable()->comment('名字');
            $table->integer('size')->unsigned()->comment('文件大小');
            $table->string('ext')->nullable()->comment('扩展名');
			$table->string('path')->nullable()->comment('路径');
			$table->string('md5', 32)->nullable()->comment('文件内容hash');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('files');
    }
}
