<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->string('store_code');
            $table->string('name');
            $table->string('main_category');
            $table->string('sub_category');
            $table->string('description')->nullable();
            $table->decimal('weight')->nullable();
            $table->integer('stock')->nullable();
            $table->string('color')->nullable();
            $table->decimal('width')->nullable();
            $table->decimal('height')->nullable();
            $table->decimal('length')->nullable();
            $table->bigInteger('price')->nullable();
            $table->timestamps();
        });

        $sequence = "DROP SEQUENCE IF EXISTS product_seq;
                        CREATE SEQUENCE product_seq
                        start with 1
                        increment by 1
                        minvalue 0
                        nocycle";

        $function = "DROP FUNCTION IF EXISTS getProductSequence;
                        CREATE FUNCTION getProductSequence() RETURNS VARCHAR(8) DETERMINISTIC BEGIN
                            DECLARE
                                hasil VARCHAR(8) ;
                            SET
                                hasil = CONCAT(
                                    'PRD',
                                    LPAD(NEXT
                                VALUE FOR
                                    product_seq, 5, '0')
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
        Schema::dropIfExists('tb_product');
    }
}
