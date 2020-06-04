<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_cart', function(Blueprint $table){
          $table->text('message')->after('username')->nullable();
          $table->boolean('checkout_status')->after('message')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('tb_cart', function(Blueprint $table){
        $table->dropColumn('message');
        $table->dropColumn('checkout_status');
      });
    }
}
