<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductDetailSeeder extends Seeder
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

        DB::table('tb_product_detail')->delete();

        DB::table('tb_product_detail')->insert([
            'product_code' => $this->getProductCodeWithSizeCategory(),
            'size' => 'S',
            'stock' => 23,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_product_detail')->insert([
            'product_code' => $this->getProductCodeWithSizeCategory(),
            'size' => 'M',
            'stock' => 23,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    private function getProductCodeWithSizeCategory()
    {
        $sizeCode = \App\Category::where('name', 'Pakaian')->first();
        $product = \App\Product::where('main_category', $sizeCode['code'])->first();
        return $product->code;
    }

    private function getSizeValue()
    {
        $status = \App\Status::where('name', 'Ukuran')->first();
        $value = \App\StatusDetail::where('status_code', $status->code)->first();
        return $value->code;
    }
}
