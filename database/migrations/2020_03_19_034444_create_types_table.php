<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesTable extends Migration
{
    /**
     * Run the migrations.
     * 类型
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_zh')->comment('中文名');
            $table->string('name_en')->nullable()->comment('英文名');
            $table->string('image_url')->comment('图片路径');
            $table->unsignedInteger('sort_num')->default(0)->comment('排序');
            $table->boolean('on_sale')->default(true)->comment('是否显示');

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
        Schema::dropIfExists('types');
    }
}
