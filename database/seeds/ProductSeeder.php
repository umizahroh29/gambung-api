<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
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

        DB::table('tb_product')->delete();

        DB::table('tb_product')->insert([
            'code' => $masterdata->getProductSequence(),
            'name' => 'Kopi Civet',
            'store_code' => $this->getRandomStoreCode(),
            'main_category' => $this->getRandomCategoryCode(),
            'sub_category' => '',
            'description' => 'MENGAPA , KOPI Civet?? Kami hanya menggunakan Kopi-Kopi Asli Toraja yang mengunakan PUPUK ORGANIK (baik untuk kesehatan) yang Langsung kami Petik dari Perkebunan kami.',
            'weight' => 0.5,
            'stock' => 10,
            'price' => 30000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_product')->insert([
            'code' => $masterdata->getProductSequence(),
            'name' => 'Kopi Civet 2',
            'store_code' => $this->getRandomStoreCode(),
            'main_category' => $this->getRandomCategoryCode(),
            'sub_category' => '',
            'description' => 'MENGAPA , KOPI Civet?? Kami hanya menggunakan Kopi-Kopi Asli Toraja yang mengunakan PUPUK ORGANIK (baik untuk kesehatan) yang Langsung kami Petik dari Perkebunan kami.',
            'weight' => 0.3,
            'stock' => 12,
            'price' => 35000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_product')->insert([
            'code' => $masterdata->getProductSequence(),
            'name' => 'Kopi Goreng',
            'store_code' => $this->getRandomStoreCode(),
            'main_category' => $this->getRandomCategoryCode(),
            'sub_category' => '',
            'description' => 'MENGAPA , KOPI goreng ? ya biar digoreng',
            'weight' => 0.7,
            'stock' => 25,
            'price' => 10000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    private function getRandomCategoryCode()
    {
        $category = \App\Category::inRandomOrder()->first();
        return $category->code;
    }

    private function getRandomStoreCode()
    {
        $store = \App\Store::inRandomOrder()->first();
        return $store->code;
    }
}
