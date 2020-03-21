<?php

use Illuminate\Database\Seeder;

class ProductTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Product::class,100)->create();

    }
}
