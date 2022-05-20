<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('project_statuses')->insert([
            ['status' => 'Thêm mới'],
            ['status' => 'Đang tiến hành'],
            ['status' => 'Đã kết thúc'],
            ['status' => 'Đã hủy'],
        ]);
    }
}
