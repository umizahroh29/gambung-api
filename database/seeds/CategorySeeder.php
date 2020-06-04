<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategorySeeder extends Seeder
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

        DB::table('tb_category')->delete();

        DB::table('tb_category')->insert([
            'code' => $masterdata->getMainCategorySequence(),
            'name' => 'Makanan',
            'level' => 'Main Category',
            'main_category' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_category')->insert([
            'code' => $masterdata->getMainCategorySequence(),
            'name' => 'Kopi',
            'level' => 'Main Category',
            'main_category' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('tb_category')->insert([
            'code' => $masterdata->getMainCategorySequence(),
            'name' => 'Pakaian',
            'level' => 'Main Category',
            'main_category' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
