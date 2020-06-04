<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->string('level');
            $table->string('main_category')->nullable();
            $table->timestamps();
        });

        $mainSequence = "DROP SEQUENCE IF EXISTS category_seq;
                        CREATE SEQUENCE category_seq
                        start with 1
                        increment by 1
                        minvalue 0
                        nocycle";

        $mainFunction = "DROP FUNCTION IF EXISTS getCategorySequence;
                        CREATE FUNCTION getCategorySequence() RETURNS VARCHAR(5) DETERMINISTIC BEGIN
                            DECLARE
                                hasil VARCHAR(5) ;
                            SET
                                hasil = CONCAT(
                                    'MCT',
                                    LPAD(NEXT
                                VALUE FOR
                                    product_seq, 2, '0')
                                ) ; RETURN hasil ;
                        END";

        $subSequence = "DROP SEQUENCE IF EXISTS sub_category_seq;
                        CREATE SEQUENCE sub_category_seq
                        start with 1
                        increment by 1
                        minvalue 0
                        maxvalue 999
                        nocycle";

        $subFunction = "DROP FUNCTION IF EXISTS getSubCategorySequence;
                        CREATE FUNCTION getSubCategorySequence() RETURNS VARCHAR(6) DETERMINISTIC BEGIN
                            DECLARE
                                hasil VARCHAR(6) ;
                            SET
                                hasil = CONCAT(
                                    'SCT',
                                    LPAD(NEXT
                                VALUE FOR
                                    product_seq, 3, '0')
                                ) ; RETURN hasil ;
                        END";

        DB::unprepared($mainSequence);
        DB::unprepared($mainFunction);
        DB::unprepared($subSequence);
        DB::unprepared($subFunction);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_category');
    }
}
