<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create('id_ID');

        for ($i=0; $i < 6; $i++) {
          DB::table('messages')->insert([
            'from_user' => 4,
            'to_user' => 5,
            'message' => $faker->realText($maxNbChars = 200, $indexSize = 2),
            'created_at' => Carbon::now(),
          ]);

          DB::table('messages')->insert([
            'from_user' => 5,
            'to_user' => 4,
            'message' => $faker->realText($maxNbChars = 200, $indexSize = 2),
            'created_at' => Carbon::now(),
          ]);
        }

    }
}
