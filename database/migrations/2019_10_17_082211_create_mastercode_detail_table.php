<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMastercodeDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_mastercode_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('parent_code');
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('parent_code')->references('code')->on('tb_mastercode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_mastercode_detail');
    }
}
