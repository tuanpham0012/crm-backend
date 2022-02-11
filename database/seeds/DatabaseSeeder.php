<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call([
            CreateSuperAdmin::class,
            CreateDepartment::class,
            CreateRole::class,
            CreateUserStatus::class,
            CreateCustomerStatus::class,
            CreateCustomerType::class,
        ]);
    }
}

class CreateSuperAdmin extends Seeder{
    public function run(){
        DB::table('users')->insert([
            'employee_code' => 'ssss-ssss-ssss-ssss',
            'name' => 'Quản trị viên',
            'email' => 'tuanpham0012@gmail.com',
            'phone' => '0983776901',
            'date_of_birth' => '',
            'gender' => 'male',
            'status' => '',
            'password' => Hash::make('admin123'),
            'role_id' => 1
        ]);
    }
}
class CreateDepartment extends Seeder{
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
class CreateRole extends Seeder{
    public function run(){
        DB::table('role')->insert([
            ['role' => 'Quản trị viên'],
            ['role' => 'Quản lý'],
            ['role' => 'Nhân viên']
        ]);
    }
}
class CreateUserStatus extends Seeder{
    public function run(){
        DB::table('user_status')->insert([
            ['status' => 'Đang hoạt động'],
            ['status' => 'Tạm nghỉ'],
            ['status' => 'Đã xóa']
        ]);
    }
}
class CreateCustomerStatus extends Seeder{
    public function run(){
        DB::table('customer_status')->insert([
            ['status' => 'Thêm mới'],
            ['status' => 'Trùng thông tin'],
            ['status' => 'Khác']
        ]);
    }
}
class CreateCustomerType extends Seeder{
    public function run(){
        DB::table('type_of_customer')->insert([
            ['type' => 'Khách hàng mới'],
            ['type' => 'Khách hàng tư vấn'],
            ['type' => 'Khách hàng hẹn lịch'],
            ['type' => 'Khách hàng cs sau bán'],
            ['type' => 'Khách hàng từ chối']
        ]);
    }
}



