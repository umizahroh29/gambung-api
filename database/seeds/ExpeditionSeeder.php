<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ExpeditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');

        DB::table('tb_expedition')->delete();

        DB::table('tb_expedition')->insert([
            'code' => 'tiki',
            'name' => 'TIKI',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_expedition')->insert([
            'code' => 'jne',
            'name' => 'JNE',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
