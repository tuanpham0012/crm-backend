<?php

use App\Models\NoteOfTask;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use vendor\autoload;

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
            DepartmentSeeder::class,
            PossitionSeeder::class,
            RoleSeeder::class,
            UserStatusSeeder::class,
            UserSeeder::class,
            CustomerStatusSeeder::class,
            CustomerTypeSeeder::class,
            CustomerSeeder::class,
            UnitSeeder::class,
            ProductTypeSeeder::class,
            ProductsSeeder::class,
            ProjectStatusSeeder::class,
            TaskStatusSeeder::class,
            TypeOfTaskSeeder::class,
            TaskSeeder::class,
            NoteOfTaskSeeder::class,
        ]);
    }
}




