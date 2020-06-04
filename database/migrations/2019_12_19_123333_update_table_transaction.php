<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('tb_transaction_detail', function(Blueprint $table){
        $table->text('message')->after('price')->nullable();
        $table->string('expedition')->after('product_code');
      });

      Schema::table('tb_transaction', function(Blueprint $table){
        $table->dropForeign('tb_transaction_expedition_code_foreign');
        $table->dropColumn('expedition_code');
        $table->text('address_1')->change();
        $table->text('address_2')->change();
        $table->text('address_3')->change();
        $table->renameColumn('name', 'username');
      });

      Schema::table('tb_transaction_payment', function(Blueprint $table){
        $table->decimal('account_number')->nullable()->change();
        $table->string('account_name')->nullable()->change();
        $table->string('account_bank')->nullable()->change();
      });

      Schema::table('users', function(Blueprint $table){
        $table->text('address_1')->change();
        $table->text('address_2')->change();
        $table->text('address_3')->change();
      });

      Schema::table('tb_store', function(Blueprint $table){
        $table->text('address_1')->change();
        $table->text('address_2')->change();
        $table->text('address_3')->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('tb_transaction_detail', function(Blueprint $table){
        $table->dropColumn('expedition');
        $table->dropColumn('message');
      });

      Schema::table('tb_transaction', function(Blueprint $table){
        $table->renameColumn('username', 'name');
      });

      Schema::table('tb_transaction_payment', function(Blueprint $table){
        $table->decimal('account_number')->change();
        $table->string('account_name')->change();
        $table->string('account_bank')->change();
      });

    }
}
