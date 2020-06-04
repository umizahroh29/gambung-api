<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class ReviewsSeeder extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    $faker = Faker::create('id_ID');

    for ($i=0; $i < 2; $i++) {
      DB::table('reviews')->insert([
        'id_users' => 5,
        'product_code' => 'PRD0000002',
        'review' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'created_at' => Carbon::now(),
      ]);
    }
    for ($i=0; $i < 3; $i++) {
      DB::table('reviews')->insert([
        'id_users' => 5,
        'product_code' => 'PRD0000001',
        'review' => $faker->realText($maxNbChars = 200, $indexSize = 2),
        'created_at' => Carbon::now(),
      ]);
    }
  }
}
