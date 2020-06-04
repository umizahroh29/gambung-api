<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnJicashTransactionPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_transaction_payment', function(Blueprint $table){
            $table->unsignedBigInteger('payment_method_id')->after('transaction_code')->nullable();
            $table->foreign('payment_method_id')->references('id')->on('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_transaction_payment', function(Blueprint $table){
            $table->dropForeign('tb_transaction_payment_payment_method_id');
            $table->dropColumn('payment_method_id');
        });
    }
}
