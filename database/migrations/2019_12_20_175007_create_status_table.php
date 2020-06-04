<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();

            $sequence = "DROP SEQUENCE IF EXISTS status_seq;
                        CREATE SEQUENCE status_seq
                        start with 1
                        increment by 1
                        minvalue 0
                        nocycle";

            $function = "DROP FUNCTION IF EXISTS getStatusSequence;
                        CREATE FUNCTION getStatusSequence() RETURNS VARCHAR(5) DETERMINISTIC BEGIN
                            DECLARE
                                hasil VARCHAR(8) ;
                            SET
                                hasil = CONCAT(
                                    'STS',
                                    LPAD(NEXT
                                VALUE FOR
                                    status_seq, 2, '0')
                                ) ; RETURN hasil ;
                        END";

            DB::unprepared($sequence);
            DB::unprepared($function);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_status');
    }
}
