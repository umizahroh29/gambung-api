<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('reviews', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('id_users')->unsigned();
      $table->string('product_code');
      $table->text('review');
      $table->timestamps();

      $table->foreign('id_users')->references('id')->on('users');
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
    Schema::dropIfExists('reviews');
  }
}
