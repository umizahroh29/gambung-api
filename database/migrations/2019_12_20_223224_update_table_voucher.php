<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('tb_voucher_terms', function(Blueprint $table){
        $table->dropColumn('term');
        $table->string('store_code')->after('voucher_code');

        $table->foreign('store_code')->references('code')->on('tb_store');
      });

      Schema::table('tb_voucher', function(Blueprint $table){
        $table->text('terms')->after('max_price');
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('tb_voucher_terms', function(Blueprint $table){
        $table->dropForeign('tb_voucher_terms_store_code_foreign');
        $table->string('term');
        $table->dropColumn('store_code');
      });

      Schema::table('tb_voucher', function(Blueprint $table){
        $table->dropColumn('terms');
      });
    }
}
