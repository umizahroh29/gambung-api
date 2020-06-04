<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
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

        DB::table('tb_transaction')->delete();

        DB::table('tb_transaction')->insert([
            'code' => $masterdata->getTransactionSequence(),
            'username' => $this->getRandomUser(),
            'address_1' => 'JL Sukabirus Bojongsoang Kabupaten Bandung',
            'phone' => '081390887271',
            'total_product' => 1,
            'total_quantity' => 1,
            'total_weight' => 0.5,
            'shipping_charges' => 10000,
            'total_amount' => 30000,
            'discount_pct' => 0,
            'discount_amount' => 0,
            'grand_total_amount' => 40000,
            'created_by' => Carbon::now(),
            'updated_by' => Carbon::now()
        ]);
    }

    private function getRandomUser()
    {
        $user = \App\User::inRandomOrder()->first();
        return $user->username;
    }
}
