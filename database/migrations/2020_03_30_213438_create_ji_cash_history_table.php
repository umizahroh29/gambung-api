<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJiCashHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ji_cash_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ji_cash_id');
            $table->string('transaction_type');
            $table->decimal('amount', 12);
            $table->string('is_topup_approved')->default('OPTNO');
            $table->string('topup_proof_image')->nullable();
            $table->string('is_withdrawal')->default('OPTNO');
            $table->string('withdrawal_approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ji_cash_history');
    }
}
