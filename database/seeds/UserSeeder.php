<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CreateAdmin::class);
        factory(User::class, 100)->create();
        $this->call(AddStaff::class);
    }
}

class CreateAdmin extends Seeder{
    public function run(){
        DB::table('users')->insert([
            'employee_code' => 'ssss-ssss-ssss-ssss',
            'name' => 'Quản trị viên',
            'email' => 'tuanpham0012@gmail.com',
            'email_verified_at' => now(),
            'phone' => '0983776901',
            'date_of_birth' => '',
            'gender' => 'Nam',
            'status' => '',
            'password' => Hash::make('admin123'),
            'role_id' => 1,
            'created_at' => new DateTime,
            'updated_at' => new DateTime,
        ]);
    }
}
class AddStaff extends Seeder{
    public function run(){
        $users = User::get();
        foreach($users as $user){
            DB::table('staff_department')->insert([
                'department_id' => Department::query()->inRandomOrder()->value('id'),
                'user_id' => $user->id,
                'position_id' => 3
            ]);
        }
    }
}
