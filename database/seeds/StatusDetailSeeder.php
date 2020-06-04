<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class StatusDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Jakarta');

        DB::table('tb_status_detail')->delete();
        $values = json_decode(File::get(database_path('sources/status_category_value.json')), true);

        foreach ($values['Ukuran'] as $value) {
            DB::table('tb_status_detail')->insert([
                'status_code' => $this->getSizeStatusCode(),
                'value' => $value,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }

    private function getSizeStatusCode()
    {
        $status = \App\Status::where('name', 'Ukuran')->first();
        return $status->code;
    }
}
