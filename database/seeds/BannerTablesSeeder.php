<?php

use Illuminate\Database\Seeder;

class BannerTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Banner::class,4)->create();
    }
}
