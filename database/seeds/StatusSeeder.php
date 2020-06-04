<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');
        $masterdata = new \App\Http\Controllers\MasterdataController();

        DB::table('tb_status')->delete();

        DB::table('tb_status')->insert([
            'code' => $masterdata->getStatusSequence(),
            'name' => 'Ukuran',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_status')->insert([
            'code' => $masterdata->getStatusSequence(),
            'name' => 'Dimensi',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_status')->insert([
            'code' => $masterdata->getStatusSequence(),
            'name' => 'Warna',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
