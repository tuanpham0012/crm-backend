<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            ['role' => 'Quản trị viên'],
            ['role' => 'Quản lý'],
            ['role' => 'Nhân viên']
        ]);
    }
}
