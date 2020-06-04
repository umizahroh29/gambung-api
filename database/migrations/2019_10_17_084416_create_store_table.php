<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_store', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('username');
            $table->string('description')->nullable();
            $table->string('address_1', 60);
            $table->string('address_2', 60)->nullable();
            $table->string('address_3', 60)->nullable();
            $table->string('phone_1', 13);
            $table->string('phone_2', 13)->nullable();
            $table->string('city');
            $table->timestamps();
        });

        $sequence = "DROP SEQUENCE IF EXISTS store_seq;
                        CREATE SEQUENCE store_seq
                        start with 1
                        increment by 1
                        minvalue 0
                        nocycle";

        $function = "DROP FUNCTION IF EXISTS getStoreSequence;
                        CREATE FUNCTION getStoreSequence() RETURNS VARCHAR(8) DETERMINISTIC BEGIN
                            DECLARE
                                hasil VARCHAR(8) ;
                            SET
                                hasil = CONCAT(
                                    'STR',
                                    LPAD(NEXT
                                VALUE FOR
                                    store_seq, 5, '0')
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
        Schema::dropIfExists('tb_store');
    }
}
