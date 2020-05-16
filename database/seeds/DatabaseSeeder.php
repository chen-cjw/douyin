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
         $this->call(BannerTablesSeeder::class);
         $this->call(CategoryTablesSeeder::class);
         $this->call(ProductTablesSeeder::class);
         $this->call(TypeTablesSeeder::class);
         $this->call(UserSeeder::class);
    }
}
