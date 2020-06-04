<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_transaction_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_code');
            $table->string('product_code');
            $table->decimal('quantity');
            $table->decimal('weight');
            $table->decimal('price');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->foreign('transaction_code')->references('code')->on('tb_transaction');
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
        Schema::dropIfExists('tb_transaction_detail');
    }
}
