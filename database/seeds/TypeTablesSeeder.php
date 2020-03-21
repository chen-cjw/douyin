<?php

use Illuminate\Database\Seeder;

class TypeTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Type::class,5)->create();

    }
}
