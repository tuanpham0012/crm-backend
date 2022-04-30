<?php

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Customer::class, 200)->create();
        $this->call(CreateCustomer::class);
    }
}
Class CreateCustomer extends Seeder{
    public function run(){
        $faker = Faker\Factory::create();
        $customers = Customer::get();
        foreach($customers as $customer){
            DB::table('customer_phone')->insert([
                'customer_id' => $customer->id,
                'phone' => $faker->phoneNumber,
            ]);
            DB::table('customer_notes')->insert([
                'user_id' => 1,
                'customer_id' => $customer->id,
                'content' => 'Thêm mới khách hàng',
                'created_at' => new DateTime,
                'updated_at' => new DateTime,
            ]);
        }
    }
}
