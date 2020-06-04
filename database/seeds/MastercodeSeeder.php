<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MastercodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	date_default_timezone_set('Asia/Jakarta');

    	DB::table('tb_mastercode')->delete();

    	DB::table('tb_mastercode')->insert([
    		'code' => 'ROL',
    		'description' => 'Parent Code Role User',
    		'created_at' => Carbon::now(),
          	'updated_at' => Carbon::now(),
    	]);

    	DB::table('tb_mastercode')->insert([
    		'code' => 'OPT',
    		'description' => 'Parent Code Option Yes or No',
    		'created_at' => Carbon::now(),
          	'updated_at' => Carbon::now(),
    	]);
    }
}
