<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StatusCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');

        DB::table('tb_status_category')->delete();

        DB::table('tb_status_category')->insert([
            'category_code' => $this->getRandomCategoryCode(),
            'status_Code' => $this->getRandomStatusCode(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_status_category')->insert([
            'category_code' => $this->getRandomCategoryCode(),
            'status_Code' => $this->getRandomStatusCode(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_status_category')->insert([
            'category_code' => $this->getRandomCategoryCode(),
            'status_Code' => $this->getRandomStatusCode(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    private function getRandomCategoryCode()
    {
        $category = \App\Category::inRandomOrder()->first();
        return $category->code;
    }

    private function getRandomStatusCode()
    {
        $status = \App\Status::inRandomOrder()->first();
        return $status->code;
    }
}
