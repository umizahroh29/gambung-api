<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_transaction', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('address_1', 60);
            $table->string('address_2', 60)->nullable();
            $table->string('address_3', 60)->nullable();
            $table->string('phone');
            $table->string('description')->nullable();
            $table->decimal('total_product');
            $table->decimal('total_quantity');
            $table->decimal('total_weight');
            $table->string('voucher_code')->nullable();
            $table->string('expedition_code');
            $table->decimal('shipping_charges');
            $table->decimal('total_amount');
            $table->decimal('discount_pct');
            $table->decimal('discount_amount');
            $table->decimal('grand_total_amount');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->foreign('voucher_code')->references('code')->on('tb_voucher');
            $table->foreign('expedition_code')->references('code')->on('tb_expedition');
        });

        $sequence = "DROP SEQUENCE IF EXISTS transaction_seq;
                        CREATE SEQUENCE transaction_seq
                        start with 1
                        increment by 1
                        minvalue 0
                        nocycle";

        $function = "DROP FUNCTION IF EXISTS getTransactionSequence;
                        CREATE FUNCTION getTransactionSequence() RETURNS VARCHAR(10) DETERMINISTIC BEGIN
                            DECLARE
                                hasil VARCHAR(10) ;
                            SET
                                hasil = CONCAT(
                                    'TRX',
                                    LPAD(NEXT
                                VALUE FOR
                                    transaction_seq, 7, '0')
                                ) ; RETURN hasil ;
                        END";

        DB::unprepared($sequence);
        DB::unprepared($function);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_transaction');
    }
}
