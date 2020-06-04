<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnResi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_transaction_detail', function(Blueprint $table){
            $table->string('shipping_status')->default('OPTNO');
            $table->string('shipping_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_transaction_detail', function (Blueprint $table) {
            $table->dropColumn('shipping_no');
            $table->dropColumn('shipping_status');
        });
    }
}
