<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [];
        for ($i = 0; $i < 1000; $i++) {
            $products[] = [
                'name' => "Product $i",
                'price' => rand(100, 10000) / 100,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table(
            'products'
        )->insert($products);
    }
}
