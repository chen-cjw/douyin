<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     * 商品
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('商品名称');
            $table->string('image_url')->comment('图片路径');
            $table->text('description')->nullable()->comment('商品详情');
            $table->string('commission_rate')->default(0)->comment('佣金比率');
            $table->decimal('discounted_price', 10, 2)->default(0)->comment('折后价格');
            $table->decimal('price', 10, 2)->default(0)->comment('价格');
            $table->decimal('favourable_price', 10, 2)->default(0)->comment('优惠卷价格');
            $table->decimal('vermicelli_consumption', 10, 0)->default(0)->comment('粉丝量');
            $table->decimal('sample_quantity', 10, 0)->default(0)->comment('样品数量');
            $table->boolean('support_dou')->default(true)->comment('支持DOU+|true:是,false:否');
            $table->boolean('support_directional')->default(true)->comment('支持定向|true:是,false:否');
            $table->string('copy_link')->nullable()->comment('复制链接');
            $table->date('activity_countdown')->nullable()->comment('活动倒计时');
            $table->boolean('on_sale')->default(true)->comment('是否显示');
            $table->unsignedInteger('sort_num')->default(0)->comment('排序');

            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
//            $table->foreign('type_id')->references('id')->on('types');
//            $table->foreign('category_id')->references('id')->on('categories');

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
