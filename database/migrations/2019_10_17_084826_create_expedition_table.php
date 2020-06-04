<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpeditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_expedition', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('api_code')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        $sequence = "DROP SEQUENCE IF EXISTS expedition_seq;
                        CREATE SEQUENCE expedition_seq
                        start with 1
                        increment by 1
                        minvalue 0
                        nocycle";

        $function = "DROP FUNCTION IF EXISTS getExpeditionSequence;
                        CREATE FUNCTION getExpeditionSequence() RETURNS VARCHAR(8) DETERMINISTIC BEGIN
                            DECLARE
                                hasil VARCHAR(8) ;
                            SET
                                hasil = CONCAT(
                                    'EXP',
                                    LPAD(NEXT
                                VALUE FOR
                                    expedition_seq, 5, '0')
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
        Schema::dropIfExists('tb_expedition');
    }
}
