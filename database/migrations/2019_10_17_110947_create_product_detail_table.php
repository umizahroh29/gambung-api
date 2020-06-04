<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_product_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_code');
            $table->string('size')->nullable();
            $table->decimal('stock')->nullable();
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
        Schema::dropIfExists('tb_product_detail');
    }
}
