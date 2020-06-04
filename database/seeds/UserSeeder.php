<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');

        DB::table('users')->delete();

        DB::table('users')->insert([
        	'email' => 'admin@gambungstore.id',
        	'name' => 'Super Admin Gambung',
        	'password' => bcrypt('AdminGambung!'),
          'role' => 'ROLSA',
          'username' => 'admin',
          'verified' => 'OPTYS',
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ]);

        // DB::table('users')->insert([
        // 	'email' => 'erza@gmail.com',
        // 	'name' => 'Erza Gambung',
        // 	'password' => bcrypt('erzaganteng'),
        //   'role' => 'ROLAD',
        //   'username' => 'erza',
        //   'verified' => 'OPTYS',
        //   'created_at' => Carbon::now(),
        //   'updated_at' => Carbon::now(),
        // ]);
        //
        // DB::table('users')->insert([
        // 	'email' => 'umi@gmail.com',
        // 	'name' => 'Umi Cantik',
        // 	'password' => bcrypt('umi'),
        //   'role' => 'ROLAD',
        //   'username' => 'umi',
        //   'verified' => 'OPTYS',
        //   'created_at' => Carbon::now(),
        //   'updated_at' => Carbon::now(),
        // ]);

        // DB::table('users')->insert([
        // 	'email' => 'penjual@gmail.com',
        // 	'name' => 'Penjual Ganteng',
        // 	'password' => bcrypt('penjual'),
        //   'role' => 'ROLPJ',
        //   'username' => 'penjual',
        //   'verified' => 'OPTNO',
        //   'created_at' => Carbon::now(),
        //   'updated_at' => Carbon::now(),
        // ]);
        //
        // DB::table('users')->insert([
        // 	'email' => 'penjual2@gmail.com',
        // 	'name' => 'Penjual 2',
        // 	'password' => bcrypt('penjual'),
        //   'role' => 'ROLPJ',
        //   'username' => 'penjual2',
        //   'verified' => 'OPTNO',
        //   'created_at' => Carbon::now(),
        //   'updated_at' => Carbon::now(),
        // ]);
        //
        // DB::table('users')->insert([
        // 	'email' => 'pembeli@gmail.com',
        // 	'name' => 'Pembeli Ganteng',
        // 	'password' => bcrypt('pembeli'),
        //   'address_1' => 'Jl Faliman Jaya RT 04/06 No 148',
        //   'address_2' => 'Jurumudi Baru, Benda',
        //   'city' => 'Tangerang',
        //   'phone' => '081313082440',
        //   'role' => 'ROLPB',
        //   'username' => 'pembeli',
        //   'verified' => 'OPTNO',
        //   'created_at' => Carbon::now(),
        //   'updated_at' => Carbon::now(),
        // ]);
    }
}
