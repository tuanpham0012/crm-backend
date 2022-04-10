<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use vendor\autoload;
use App\Models\role;
use App\Models\Department;
use App\Models\TypeCustomer;

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
            CreateDepartment::class,
            CreatePosition::class,
            CreateRole::class,
            CreateUserStatus::class,
            CreateCustomerStatus::class,
            CreateCustomerType::class,
            CreateUser::class,
            CreateCustomer::class
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
class CreatePosition extends Seeder{
    public function run(){
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
class CreateUser extends Seeder{
    public function run(){
        $role = Role::get();
        $count_role = isset($role) ? count($role) : 1;

        $deparments = Department::get();
        $count_department = isset($deparments) ? count($deparments) : 1;
        DB::table('users')->insert([
            'employee_code' => 'ssss-ssss-ssss-ssss',
            'name' => 'Quản trị viên',
            'email' => 'tuanpham0012@gmail.com',
            'email_verified_at' => now(),
            'phone' => '0983776901',
            'date_of_birth' => '',
            'gender' => 'male',
            'status' => '',
            'password' => Hash::make('admin123'),
            'role_id' => 1,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
        $faker = Faker\Factory::create();
        for($i = 1; $i < 20;$i++){
            $id = DB::table('users')->insertGetId([
                'employee_code' => Str::orderedUuid(),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'phone' => $faker->phoneNumber,
                'date_of_birth' => '',
                'gender' => 'male',
                'status' => '',
                'password' => Hash::make('admin123'),
                'role_id' => rand(1, $count_role),
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ]);
            DB::table('staff_department')->insert([
                'department_id' => rand(1, $count_department),
                'user_id' => $id,
                'position_id' => 1
            ]);
        }
    }
}
Class CreateCustomer extends Seeder{
    public function run(){
        $faker = Faker\Factory::create();
        $type = TypeCustomer::get();
        $count_type = isset($type) ? count($type) : 1;
        for($i = 1; $i < 20;$i++){
            $id = DB::table('customers')->insertGetId([
                'customer_code' => Str::orderedUuid(),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'address' => $faker->address,
                'gender' => 'male',
                'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'type_of_customer_id' => rand(1, $count_type),
                'status' => 1,
                'note' => '',
                'deleted' => 0,
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ]);
            DB::table('customer_phone')->insert([
                'customer_id' => $id,
                'phone' => $faker->phoneNumber,
            ]);
            DB::table('customer_notes')->insert([
                'user_id' => 1,
                'customer_id' => $id,
                'content' => 'Tạo mới khách hàng'
            ]);
        } 
    }
}



