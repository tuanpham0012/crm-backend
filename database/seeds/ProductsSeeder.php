<?php

use Illuminate\Database\Seeder;
use App\Models\TypeProduct;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $type = TypeProduct::get();
        $count_type = isset($type) ? count($type) : 1;
        for($i = 0; $i < 200; $i++){
            DB::table('products')->insert([
                [
                    'code_product' => $faker->unique()->macAddress,
                    'name' => $faker->name,
                    'type_of_product_id' => rand(1, $count_type),
                    'origin' => $faker->country,
                    'unit'  => 'Tấn',
                    'describe' => $faker->text($maxNbChars = 200),
                    'VAT' => '10',
                    'created_at' => new DateTime,
                    'updated_at' => new DateTime,
                ],
            ]);
        }
        
    }
}
