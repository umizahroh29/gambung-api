<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StoreExpeditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');

        DB::table('tb_store_expedition')->delete();

        DB::table('tb_store_expedition')->insert([
            'expedition_code' => 'jne',
            'store_code' => $this->getRandomStoreCode(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_store_expedition')->insert([
            'expedition_code' => 'tiki',
            'store_code' => $this->getRandomStoreCode(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    private function getRandomStoreCode()
    {
        $store = \App\Store::inRandomOrder()->first();
        return $store->code;
    }

    private function getRandomExpeditionCode()
    {
        $expedition = \App\Expedition::inRandomOrder()->first();
        return $expedition->code;
    }
}
