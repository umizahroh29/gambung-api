<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_transaction_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transaction_code');
            $table->decimal('account_number');
            $table->string('account_name');
            $table->string('account_bank');
            $table->dateTime('deadline_proof');
            $table->string('status_upload')->default('OPTNO');
            $table->string('proof_image')->nullable();
            $table->dateTime('proof_date')->nullable();
            $table->string('verified_status')->default('OPTNO');
            $table->dateTime('verified_date')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
            $table->string('updated_process');

            $table->foreign('transaction_code')->references('code')->on('tb_transaction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_transaction_payment');
    }
}
