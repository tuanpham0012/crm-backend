<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_of_customer')->insert([
            ['type' => 'Khách hàng thêm mới'],
            ['type' => 'Khách hàng đang tư vấn'],
            ['type' => 'Khách hàng hẹn lịch'],
            ['type' => 'Khách hàng liên hệ lại'],
            ['type' => 'Khách hàng chốt'],
            ['type' => 'Khách hàng Bán'],
            ['type' => 'Khách hàng Mua'],
            ['type' => 'Khách hàng Đầu tư'],
        ]);
    }
}
