<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customer_status')->insert([
            ['status' => 'Thêm mới'],
            ['status' => 'Trùng thông tin'],
            ['status' => 'Khác']
        ]);
    }
}
