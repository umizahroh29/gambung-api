<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_status_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_code');
            $table->string('status_code');
            $table->timestamps();

            $table->foreign('category_code')->references('code')->on('tb_category');
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
        Schema::dropIfExists('tb_status_category');
    }
}
