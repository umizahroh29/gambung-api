<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MastercodeDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');

    	DB::table('tb_mastercode_detail')->delete();

    	DB::table('tb_mastercode_detail')->insert([
    		'parent_code' => 'ROL',
    		'code' => 'ROLSA',
    		'description' => 'ROLE SUPER ADMIN',
    		'created_at' => Carbon::now(),
          	'updated_at' => Carbon::now(),
    	]);

    	DB::table('tb_mastercode_detail')->insert([
    		'parent_code' => 'ROL',
    		'code' => 'ROLAD',
    		'description' => 'ROLE ADMIN',
    		'created_at' => Carbon::now(),
          	'updated_at' => Carbon::now(),
    	]);

    	DB::table('tb_mastercode_detail')->insert([
    		'parent_code' => 'ROL',
    		'code' => 'ROLPJ',
    		'description' => 'ROLE PENJUAL',
    		'created_at' => Carbon::now(),
          	'updated_at' => Carbon::now(),
    	]);

    	DB::table('tb_mastercode_detail')->insert([
    		'parent_code' => 'ROL',
    		'code' => 'ROLPB',
    		'description' => 'ROLE PEMBELI',
    		'created_at' => Carbon::now(),
          	'updated_at' => Carbon::now(),
    	]);

		DB::table('tb_mastercode_detail')->insert([
    		'parent_code' => 'OPT',
    		'code' => 'OPTYS',
    		'description' => 'OPTION YES',
    		'created_at' => Carbon::now(),
          	'updated_at' => Carbon::now(),
    	]);

    	DB::table('tb_mastercode_detail')->insert([
    		'parent_code' => 'OPT',
    		'code' => 'OPTNO',
    		'description' => 'OPTION NO',
    		'created_at' => Carbon::now(),
          	'updated_at' => Carbon::now(),
    	]);
    }
}
