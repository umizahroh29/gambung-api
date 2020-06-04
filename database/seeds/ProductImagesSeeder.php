<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');

        DB::table('tb_product_images')->delete();

        DB::table('tb_product_images')->insert([
            'product_code' => $this->getRandomProductCode(),
            'image_name' => '/coffee-civet.png',
            'main_image' => 'OPTNO',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_product_images')->insert([
            'product_code' => $this->getRandomProductCode(),
            'image_name' => '/coffee-civet.png',
            'main_image' => 'OPTNO',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_product_images')->insert([
            'product_code' => $this->getRandomProductCode(),
            'image_name' => '/coffee-civet.png',
            'main_image' => 'OPTNO',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_product_images')->insert([
            'product_code' => $this->getRandomProductCode(),
            'image_name' => '/coffee-civet.png',
            'main_image' => 'OPTNO',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //nanti hapus aja, tiap produk kan harus ada mainimage
        DB::table('tb_product_images')->insert([
            'product_code' => 'PRD00004',
            'image_name' => '/coffee-civet.png',
            'main_image' => 'OPTYS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_product_images')->insert([
            'product_code' => 'PRD00005',
            'image_name' => '/coffee-civet.png',
            'main_image' => 'OPTYS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_product_images')->insert([
            'product_code' => 'PRD00006',
            'image_name' => '/coffee-civet.png',
            'main_image' => 'OPTYS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }

    private function getRandomProductCode()
    {
        $product = \App\Product::inRandomOrder()->first();
        return $product->code;
    }
}
