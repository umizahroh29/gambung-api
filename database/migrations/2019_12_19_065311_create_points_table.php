<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->bigInteger('id_users')->unsigned();
          $table->bigInteger('id_transaction')->unsigned();
          $table->bigInteger('saldo');
          $table->timestamps();

          $table->foreign('id_users')->references('id')->on('users');
          $table->foreign('id_transaction')->references('id')->on('tb_transaction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points');
    }
}
