<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnAddressAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('address_1')->change();
            $table->text('address_2')->nullable()->change();
            $table->text('address_3')->nullable()->change();
        });

        Schema::table('tb_store', function (Blueprint $table) {
            $table->text('address_1')->change();
            $table->text('address_2')->nullable()->change();
            $table->text('address_3')->nullable()->change();
        });

        Schema::table('tb_transaction', function (Blueprint $table) {
            $table->text('address_1')->change();
            $table->text('address_2')->nullable()->change();
            $table->text('address_3')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
