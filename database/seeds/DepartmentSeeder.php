<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        DB::table('departments')->insert([
            [
                'code_department' => 'PBQL',
                'name' => 'Phòng quản lý'
            ],
            [
                'code_department' => 'PBSL',
                'name' => 'Phòng kinh doanh'
            ],
            [
                'code_department' => 'PPMK',
                'name' => 'Phòng tiếp thị'
            ],
            [
                'code_department' => 'PPCS',
                'name' => 'Phòng chăm sóc khách hàng'
            ]
        ]);
    }
}
