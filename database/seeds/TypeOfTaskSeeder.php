<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeOfTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_of_tasks')->insert([
            ['type' => 'Gọi điện'],
            ['type' => 'Hẹn gặp'],
            ['type' => 'Gửi mail'],
            ['type' => 'Gửi SMS'],
            ['type' => 'Hợp đồng'],
            ['type' => 'Tư vấn'],
            ['type' => 'Hỗ trợ'],
            ['type' => 'Khác']
        ]);
    }
}
