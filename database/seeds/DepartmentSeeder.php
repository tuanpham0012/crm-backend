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
                'code_department' => 'PBSL',
                'name' => 'Phòng nhân sự'
            ],
            [
                'code_department' => 'PPCS',
                'name' => 'Phòng chăm sóc khách hàng'
            ],
        ]);
    }
}
