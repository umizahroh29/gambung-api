<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StoreSeeder extends Seeder
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

        DB::table('tb_store')->delete();

        DB::table('tb_store')->insert([
            'code' => $masterdata->getStoreSequence(),
            'name' => 'Toko Laris Manis Lancar Jaya',
            'username' => $this->getRandomUser(),
            'description' => 'Toko serba ada, apa yang anda cari semua ada disini',
            'address_1' => 'Gambung',
            'phone_1' => '081237872881',
            'city' => 'Bandung',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_store')->insert([
            'code' => $masterdata->getStoreSequence(),
            'name' => 'Toko Laris Manis 2',
            'username' => $this->getRandomUser(),
            'description' => 'Toko serba ada, apa yang anda cari semua ada disini',
            'address_1' => 'Gambung 2',
            'phone_1' => '081237872881',
            'city' => 'Bandung',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    private function getRandomUser()
    {
        $user = \App\User::inRandomOrder()->first();
        return $user->username;
    }
}
