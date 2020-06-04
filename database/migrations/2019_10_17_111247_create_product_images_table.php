<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_product_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_code');
            $table->string('image_name');
            $table->string('main_image');
            $table->timestamps();

            $table->foreign('product_code')->references('code')->on('tb_product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_product_images');
    }
}
