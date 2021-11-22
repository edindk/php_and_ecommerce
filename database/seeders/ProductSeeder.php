<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 1; $i < 20; $i++) {
            Storage::disk('local')->makeDirectory('public/productImages/' . $i);
            DB::table('products')->insert([
                'name' => $faker->name,
                'productCategoryID' => 1,
                'description' => $faker->sentence(10),
                'partNumber' => $faker->numberBetween(10000000, 90000000),
                'price' => $faker->randomFloat(1.0, 200000, 0),
                'inStock' => $faker->numberBetween(1, 10),
                'isActive' => $faker->boolean,
                'imageFile' => 'public/productImages/' . $i
            ]);
        }
    }
}
