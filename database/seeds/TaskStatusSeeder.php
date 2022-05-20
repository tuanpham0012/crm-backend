<?php

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('task_statuses')->insert([
            ['status' => 'Thêm mới'],
            ['status' => 'Đang tiến hành'],
            ['status' => 'Chậm tiến độ'],
            ['status' => 'Hoàn thành muộn'],
            ['status' => 'Hoàn thành'],
            ['status' => 'Đã hủy'],
        ]);
    }
}
