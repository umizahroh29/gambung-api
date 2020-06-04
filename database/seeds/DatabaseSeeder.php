<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UsersTableSeeder::class);
        $this->call(UserSeeder::class);
//        $this->call(MastercodeSeeder::class);
//        $this->call(MastercodeDetailSeeder::class);
        $this->call(ExpeditionSeeder::class);
//        $this->call(CategorySeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(StatusDetailSeeder::class);
//        $this->call(StatusCategorySeeder::class);
//        $this->call(StoreSeeder::class);
//        $this->call(StoreExpeditionSeeder::class);
//        $this->call(ProductSeeder::class);
//        $this->call(ProductDetailSeeder::class);
//        $this->call(ProductImagesSeeder::class);
//        $this->call(TransactionSeeder::class);
//        $this->call(TransactionDetailSeeder::class);
//        $this->call(MessageSeeder::class);
        $this->call(PaymentMethodSeeder::class);
    }
}
