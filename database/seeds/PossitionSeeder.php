<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PossitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert([
            [
                'position_code' => 'TP',
                'position' => 'Trưởng phòng'
            ],
            [
                'position_code' => 'PTP',
                'position' => 'Phó trưởng phòng'
            ],
            [
                'position_code' => 'NV',
                'position' => 'Nhân viên'
            ],
        ]);
    }
}
