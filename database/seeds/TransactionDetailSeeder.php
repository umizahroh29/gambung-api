<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');

        DB::table('tb_transaction_detail')->delete();

        DB::table('tb_transaction_detail')->insert([
            'transaction_code' => $this->getRandomTransactionCode(),
            'product_code' => $this->getRandomProductCode(),
            'quantity' => 1,
            'weight' => 0.5,
            'price' => 30000,
            'expedition' => 'Jalur Nugraha Ekakurir (JNE) - OKE',
            'created_by' => Carbon::now(),
            'updated_by' => Carbon::now()
        ]);
    }

    private function getRandomTransactionCode()
    {
        $transaction = \App\Transaction::inRandomOrder()->first();
        return $transaction->code;
    }

    private function getRandomProductCode()
    {
        $product = \App\Product::inRandomOrder()->first();
        return $product->code;
    }
}
