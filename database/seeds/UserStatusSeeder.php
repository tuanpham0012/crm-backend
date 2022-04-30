<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_status')->insert([
            ['status' => 'Đang hoạt động'],
            ['status' => 'Tạm nghỉ'],
            ['status' => 'Đã xóa']
        ]);
    }
}
