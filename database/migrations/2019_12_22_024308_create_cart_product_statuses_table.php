<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartProductStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_product_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_cart')->unsigned();
            $table->string('status_code');
            $table->string('value');
            $table->timestamps();

            $table->foreign('id_cart')->references('id')->on('tb_cart');
            $table->foreign('status_code')->references('code')->on('tb_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_product_status');
    }
}
