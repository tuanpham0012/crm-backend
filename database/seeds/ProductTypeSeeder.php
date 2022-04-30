<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_of_product')->insert([
            ['type' => 'Nông Sản'],
            ['type' => 'Nguyên liệu công nghiệp'],
            ['type' => 'Năng lượng'],
            ['type' => 'Kim loại']
        ]);
    }
}
