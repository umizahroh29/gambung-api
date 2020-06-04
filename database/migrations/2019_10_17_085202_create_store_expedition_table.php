<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreExpeditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_store_expedition', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('expedition_code');
            $table->string('store_code');
            $table->timestamps();

            $table->foreign('expedition_code')->references('code')->on('tb_expedition');
            $table->foreign('store_code')->references('code')->on('tb_store');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_store_expedition');
    }
}
