<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_detail_status', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->bigInteger('id_detail')->unsigned();
          $table->string('status_code');
          $table->string('value');
          $table->timestamps();

          $table->foreign('id_detail')->references('id')->on('tb_transaction_detail');
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
        Schema::dropIfExists('transaction_detail_statuses');
    }
}
